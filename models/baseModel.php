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
    abstract protected function formatData($data, $null_filter = false): array;

    // Public methods
    public function create($data): bool
    {
        // Use formatData to prepare only the necessary data
        $formattedData = $this->ensureStorageCompatibility($this->formatData($data));
        // Dynamically generate column names and placeholders based on formatted data
        $columns = array_keys($formattedData);
        $placeholders = array_map(fn($col) => ":$col", $columns);
        // Construct the SQL query
        $query = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->getTableName(),
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        // Execute the query with formatted data as the values
        return $this->db->execute($query, array_combine($placeholders, array_values($formattedData)));
    }

    public function get($conditions, $select=[], $join=[]): ?array
    {
        $where_conditions_to_get = [];

        foreach ($conditions as $attr => $attr_value) {
            $where_conditions_to_get[] = [
                "attribute" => $attr,
                "operator" => "=",
                "value" => $attr_value,
            ];
        }
        $whereClause = $this->generateWhereClause($where_conditions_to_get);
        $joinClause = $this->generateJoinClause($join);
        $selectClause = $this->generateSelectClause($select, [], static::getTableName());

        $query = "SELECT {$selectClause} FROM " . static::getTableName() . " {$joinClause} ";
        if ($whereClause["clauses"]) $query .= " WHERE " . $whereClause['clauses'];

        $result = $this->db->fetch($query, $whereClause["params"]);

        return $result ? $this->normalizeFields($result) : null;
    }

    public function delete($conditions): bool
    {
        $where_conditions_to_delete = [];

        foreach ($conditions as $attr => $attr_value) {
            $where_conditions_to_delete[] = [
                "attribute" => $attr,
                "operator" => "=",
                "value" => $attr_value,
            ];
        }

        $whereClauses = $this->generateWhereClause($where_conditions_to_delete);

        return $this->db->execute(
            "DELETE FROM " . static::getTableName() . " WHERE " . $whereClauses['clauses'],
            $whereClauses['params']
        );
    }

    public function update($attributesWithValues, $conditions)
    {

        $setData = $this->generateSetClause($this->ensureStorageCompatibility($this->formatData($attributesWithValues, true)));

        $where_conditions_to_update = [];

        foreach ($conditions as $attr => $attr_value) {
            $where_conditions_to_update[] = [
                "attribute" => $attr,
                "operator" => "=",
                "value" => $attr_value,
            ];
        }

        $whereData = $this->generateWhereClause($where_conditions_to_update);

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
        $select = [],
        $aggregates = [],
        $groupBy = null
    ) {

        // Validating inputs
        $page = max(1, $page); // Ensure minimum page is 1
        $recordsPerPage = $this->validateRecordsPerPage($recordsPerPage);
        $whereClauseData = $this->generateWhereClause($conditions);
        $joinClause = $this->generateJoinClause($joins);
        $selectClause = $this->generateSelectClause($select, $aggregates, static::getTableName());
        $sortDirection = $this->validateSortDirection($sortDirection);
        $offset = ($page - 1) * $recordsPerPage;
        $params  = $whereClauseData['params'];
        $metadata = $this->generateMetadata($selectClause, $joins);

        // Construct SQL query
        $query = "SELECT {$selectClause} FROM " . static::getTableName() . " {$joinClause} ";

        if ($whereClauseData["clauses"]) $query .= " WHERE " . $whereClauseData['clauses'];

        // Add GROUP BY clause if specified
        if ($groupBy) $query .= " GROUP BY {$groupBy}";

        // Conditionally add ORDER BY clause if $sortField is defined
        if ($sortField) $query .= " ORDER BY {$sortField} {$sortDirection}";

        // var_dump($query);
        // var_dump($params);

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

    public function getEverything(
        array $conditions = [], 
        array $joins = [], 
        array $select = [], 
        array $aggregates = [], 
        String $groupBy = null, 
        String $sortField = null, 
        String $sortDirection = 'ASC'
    )
    {
        $records = [];
        $page = 1;
        $hasMore = false;

        do {
            // Fetch the current page of results
            $result = $this->getAll(
                select: $select,
                aggregates: $aggregates,
                groupBy: $groupBy,
                joins: $joins,
                conditions: $conditions,
                sortField: $sortField,
                sortDirection: $sortDirection,
                page: $page
            );
            $hasMore = $result["hasMore"];
        
            // Collect the products from the current page
            $records = array_merge($records, $result["records"] ?? []);
        
            // Increment the page number for the next iteration
            $page++;
        } while ($hasMore);

        return $records;
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
            // var_dump("Checking ========" . $attributes[$field] . "<br>");
            if ($this->isFieldTimestamps($field) && !empty($value)) {
                // var_dump("Before ========" . $attributes[$field] . "<br>");
                $attributes[$field] = $this->getTimestampString($value);
                // var_dump("After ========" . $attributes[$field] . "<br>");
            }
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

    public function getTimestampString($time, $format = "Y-m-d H-i-s"): string
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
    private function generateSelectClause(array $select, array $aggregates = [], string $main_table)
    {
    
        $selectClauses = [];
        foreach ($select as $item) {
            // Use the main table as the prefix if 'table' is not provided
            $tablePrefix = isset($item['table']) ? "{$item['table']}." : "{$main_table}.";
            // Check if an alias is specified for the column
            $selectClauses[] = isset($item['alias']) ? "{$tablePrefix}{$item['column']} AS {$item['alias']}" : "{$tablePrefix}{$item['column']}";
        }

        foreach ($aggregates as $each) {
            if (isset($each['function'], $each['column'])) {
                $function = strtoupper($each['function']);
                $column = $each['column'];
                $alias = isset($each['alias']) ? " AS {$each['alias']}" : "";
                $table = isset($each["table"]) ? $each["table"] : $main_table;
                $selectClauses[] = "{$function}({$table}.{$column}){$alias}";
            }
        }

        return empty($selectClauses) ? '*' : implode(', ', $selectClauses);
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
        $selected_tables = array_merge([$this->getTableName()], array_column($joins, 'table'));

        // Split selectClause by commas and trim each clause
        $selectFields = array_map('trim', explode(',', $selectClause));

        // Process each field in the select clause
        foreach ($selectFields as $field) {
            // Check if the field contains an aggregate function (e.g., MIN, MAX, SUM, etc.)
            if (preg_match('/\b(MIN|MAX|SUM|AVG|COUNT|GROUP_CONCAT)\b/i', $field)) {
                continue; // Skip aggregate functions
            }

            if ($field === "*") {
                $this->addAllColumnsFromTables($selected_tables, $metadata, $columns_seen);
                continue;
            }

            if (strpos($field, '.') !== false) {
                $this->processTableField($field, $metadata, $columns_seen);
            } else {
                $this->processSimpleField($field, $metadata, $columns_seen);
            }
        }

        return $metadata;
    }

    // Adds all columns from specified tables if not already in $columns_seen
    private function addAllColumnsFromTables(array $tables, array &$metadata, array &$columns_seen)
    {
        foreach ($tables as $table) {
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

    // Processes fields with table.column or table.column AS alias format
    private function processTableField(string $field, array &$metadata, array &$columns_seen)
    {
        list($table, $attribute) = explode('.', $field);

        if ($attribute === "*") {
            $this->addAllColumnsFromTables([$table], $metadata, $columns_seen);
            return;
        }

        if (strpos($attribute, "AS") !== false) {
            list($attribute, $alias) = array_map('trim', explode('AS', $attribute));
            $this->addMetadataEntry($table, $attribute, $alias, $metadata, $columns_seen);
        } else {
            $this->addMetadataEntry($table, $attribute, $attribute, $metadata, $columns_seen);
        }
    }

    // Processes fields with attribute or attribute AS alias format
    private function processSimpleField(string $field, array &$metadata, array &$columns_seen)
    {
        $table = $this->getTableName();

        if (strpos($field, "AS") !== false) {
            list($attribute, $alias) = array_map('trim', explode('AS', $field));
            $this->addMetadataEntry($table, $attribute, $alias, $metadata, $columns_seen);
        } else {
            $this->addMetadataEntry($table, $field, $field, $metadata, $columns_seen);
        }
    }

    // Adds a single column to metadata if it hasn't been added yet
    private function addMetadataEntry(string $table, string $attribute, string $alias, array &$metadata, array &$columns_seen)
    {
        if (!isset($columns_seen[$alias])) {
            $metadata[$alias] = [
                "type" => $GLOBALS["DB_METADATA"][$table][$attribute]["Type"],
                "sql_name" => "{$table}.{$attribute}"
            ];
            $columns_seen[$alias] = true;
        }
    }
}
