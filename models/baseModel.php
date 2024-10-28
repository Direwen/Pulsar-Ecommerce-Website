<?php

abstract class BaseModel
{

    protected $db;

    public function __construct($db_connection)
    {
        $this->db = $db_connection;
        $this->createTable();
    }

    // Abstract methods
    abstract public function createTable(): bool;
    abstract public static function getTableName(): string;
    abstract protected function createRaw($data): bool;

    // Public methods
    public function create($data): bool
    {
        return $this->createRaw($this->ensureStorageCompatibility($data));
    }

    public function get($conditions): ?array
    {
        $whereClauses = $this->generateWhereClause($conditions);

        $result = $this->db->fetch(
            "SELECT * FROM " . static::getTableName() . " WHERE " . $whereClauses['clauses'],
            $whereClauses['params']
        );

        return $result ? $this->normalizeFields($result) : null;
    }

    public function delete($conditions): bool
    {
        $whereClauses = $this->generateWhereClause($conditions);

        return $this->db->execute(
            "DELETE FROM " . static::getTableName() . " WHERE " . $whereClauses['clauses'],
            $whereClauses['params']
        );
    }

    public function update($attributesWithValues, $conditions)
    {

        $setData = $this->generateSetClause($this->ensureStorageCompatibility($attributesWithValues));
        $whereData = $this->generateWhereClause($this->ensureStorageCompatibility($conditions));

        return $this->db->execute(
            "UPDATE " . static::getTableName() . " SET " . $setData['clauses'] . " WHERE " . $whereData['clauses'],
            array_merge($setData['params'], $whereData['params'])
        );
    }

    public function getOrCreate($attributes, $conditions): ?array
    {
        $record = $this->get($conditions);
        if ($record) return $record;
        $this->create($attributes);
        return $this->get($conditions);
    }

    public function getTotalCount($conditions = [], $joins = []): int
    {
        $whereClauseData = $this->generateWhereClause($conditions);
        $joinClause = $this->generateJoinClause($joins);
        $params = $whereClauseData['params'];

        // Build the base query
        $query = "SELECT COUNT(*) AS count FROM " . static::getTableName() . " " . $joinClause;

        // Add the WHERE clause only if it's not empty
        if (!empty($whereClauseData['clauses'])) {
            $query .= " WHERE " . $whereClauseData['clauses'];
        }

        $result = $this->db->fetch($query, $params);

        return $result['count'] ?? 0;
    }

    public function getAll(
        $conditions = [],
        $page = 1,
        $recordsPerPage = 10,
        $sortField = null,
        $sortDirection = 'ASC',
        $joins = [],
        $select = []
    ) {

        // Validating inputs
        $page = max(1, $page); // Ensure minimum page is 1
        $recordsPerPage = $this->validateRecordsPerPage($recordsPerPage);
        $whereClauseData = $this->generateWhereClause($conditions);
        $joinClause = $this->generateJoinClause($joins);
        $selectClause = $this->generateSelectClause($select);
        $sortDirection = $this->validateSortDirection($sortDirection);
        $offset = ($page - 1) * $recordsPerPage;
        $params  = $whereClauseData['params'];
        $metadata = $this->generateMetadata($selectClause, $joins);

        // Construct SQL query
        $query = "SELECT {$selectClause} FROM " . static::getTableName() . " {$joinClause} ";

        if ($whereClauseData["clauses"]) $query .= " WHERE " . $whereClauseData['clauses'];

        // Conditionally add ORDER BY clause if $sortField is defined
        if ($sortField) $query .= " ORDER BY {$sortField} {$sortDirection}";

        // Fetch records
        $result = $this->db->fetchAll($query, $params, $recordsPerPage, $offset);

        // Count total records
        $totalCount = $this->getTotalCount($conditions, $joins);

        return [
            'records' => $this->normalizeFields($result ?? []),
            'hasMore' => ($totalCount > $page * $recordsPerPage),
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $recordsPerPage),
            'metadata' => $metadata
        ];
    }


    public function getColumnMetadata(): array
    {
        $result = $this->db->fetchAll("DESCRIBE " . static::getTableName());
        $newArray = [];
        foreach ($result as $element) {
            $newArray[$element['Field']] = array(
                "Type" => $element['Type'],
                "Null" => $element['Null'],
                "Key" => $element['Key']
            );
        }
        return $newArray;
    }

    // Protected methods
    protected function ensureStorageCompatibility(array $attributes): array
    {
        foreach ($attributes as $field => $value) {
            if ($this->isFieldTimestamps($field))
                $attributes[$field] = $this->getTimestampString($value);
        }

        return $attributes;
    }

    protected function normalizeFields(array $attributes): array
    {
        foreach ($attributes as $field => $value) {
            if ($this->isFieldTimestamps($field))
                $attributes[$field] = $this->parseSqlTimestamp($value);
        }

        return $attributes;
    }

    protected function generateWhereClause(array $conditions = []): array
    {
        $clauses = [];
        $params = [];
        $count = 0;

        foreach ($conditions as $condition) {

            $count++;

            if (is_array($condition['value']) && $condition['operator'] == 'IN') {
                $placeholders = [];
                foreach ($condition['value'] as $value) {
                    $paramKey = ":where_" . $count;
                    $placeholders[] = $paramKey;
                    $params[$paramKey] = $value; // Add each value to params with its own key
                }
                $placeholdersString = implode(',', $placeholders);
                $clauses[] = "{$condition['attribute']} IN ($placeholdersString)";
            } else {
                // Handle other operators
                $operator = $condition['operator'] ?? '=';

                // Check if the operator is LIKE and modify the value accordingly
                if (strtoupper($operator) === 'LIKE') {
                    $params[":" . "where_" . $count] = '%' . $condition['value'] . '%'; // Surround with % for LIKE
                } else {
                    $params[":" . "where_" . $count] = $condition['value'];
                }

                $clauses[] = "{$condition['attribute']} {$condition['operator']} :" . "where_" . $count;
            }
        }

        return [
            "clauses" => implode(" AND ", $clauses),
            "params" => $params
        ];
    }

    protected function generateSetClause(array $conditions): array
    {
        $clauses = [];
        $params = [];
        $setParamsCount = 0; // Counter for set parameters

        foreach ($conditions as $attribute => $value) {
            // Generate a unique placeholder for each attribute
            $setParamsCount++;
            $paramKey = ":set_{$attribute}_{$setParamsCount}";

            // Add the set clause
            $clauses[] = "{$attribute} = {$paramKey}";

            // Store the value in params
            $params[$paramKey] = $value;
        }

        return [
            "clauses" => implode(", ", $clauses), // Join clauses with commas
            "params" => $params // Return the parameters array
        ];
    }

    // Private methods

    private function isFieldTimestamps($field)
    {
        // Check if the field name ends with 'at'
        return is_string($field) && substr($field, -2) === 'at';
    }

    private function getTimestampString($time, $format = "Y-m-d H-i-s"): string
    {
        return date($format, $time);
    }

    private function parseSqlTimestamp($sql_timestamp): int
    {
        return strtotime($sql_timestamp);
    }

    // Helper function to validate records per page
    private function validateRecordsPerPage($recordsPerPage)
    {
        return ($recordsPerPage < 1 || $recordsPerPage > 100) ? 10 : $recordsPerPage;
    }

    // Helper function to generate JOIN clause
    private function generateJoinClause(array $joins)
    {
        $joinClause = '';
        foreach ($joins as $join) {
            $joinClause .= " {$join['type']} {$join['table']} ON {$join['on']}";
        }
        return $joinClause;
    }

    // Helper function to generate SELECT clause
    private function generateSelectClause(array $select)
    {
        if (empty($select)) {
            return '*'; // Default to all columns if none specified
        }

        $selectClauses = [];
        foreach ($select as $item) {
            $selectClauses[] = isset($item['alias'])
                ? "{$item['column']} AS {$item['alias']}"
                : "{$item['column']}";
        }
        return implode(', ', $selectClauses);
    }

    // Helper function to validate sort direction
    private function validateSortDirection($sortDirection)
    {
        $validSortDirections = ['ASC', 'DESC'];
        return in_array($sortDirection, $validSortDirections) ? $sortDirection : 'ASC';
    }

    private function generateMetadata($selectClause, $joins): array
    {
        $metadata = [];
        $columns_seen = []; // Track columns added to metadata to prevent duplicates
        $selected_tables = [$this->getTableName()];

        foreach ($joins as $join) {
            $selected_tables[] = $join["table"];
        }

        // Split selectClause by commas and trim each clause
        $selectFields = array_map('trim', explode(',', $selectClause));

        foreach ($selectFields as $field) {
            if (strpos($field, '.') !== false) {
                list($table, $attribute) = explode('.', $field);

                if ($attribute == "*") {
                    // Add all columns from this table
                    foreach ($GLOBALS["DB_METADATA"][$table] as $col => $info) {
                        if (!isset($columns_seen[$col])) {
                            $metadata[$col] = [
                                "type" => $info["Type"],
                                "sql_name" => "{$table}.{$col}"
                            ];
                            $columns_seen[$col] = true;
                        }
                    }
                    continue;
                }

                if (strpos($attribute, "AS") !== false) {
                    list($attribute, $alias) = array_map('trim', explode('AS', $attribute));
                    if (!isset($columns_seen[$alias])) {
                        $metadata[$alias] = [
                            "type" => $GLOBALS["DB_METADATA"][$table][$attribute]["Type"],
                            "sql_name" => "{$table}.{$attribute}"
                        ];
                        $columns_seen[$alias] = true;
                    }
                } else {
                    if (!isset($columns_seen[$attribute])) {
                        $metadata[$attribute] = [
                            "type" => $GLOBALS["DB_METADATA"][$table][$attribute]["Type"],
                            "sql_name" => "{$table}.{$attribute}"
                        ];
                        $columns_seen[$attribute] = true;
                    }
                }
            } elseif ($field == "*") {
                // Add all columns from all selected tables
                foreach ($selected_tables as $table) {
                    foreach ($GLOBALS["DB_METADATA"][$table] as $col => $info) {
                        if (!isset($columns_seen[$col])) {
                            $metadata[$col] = [
                                "type" => $info["Type"],
                                "sql_name" => "{$table}.{$col}"
                            ];
                            $columns_seen[$col] = true;
                        }
                    }
                }
            }
        }

        return $metadata;
    }
}
