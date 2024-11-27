<?php

require_once "./models/baseModel.php";

class EventProductModel extends BaseModel
{
    // Table and column constants
    public const TABLE_NAME = 'event_products';

    public const COLUMN_ID = 'id';
    public const COLUMN_EVENT_ID = 'event_id';
    public const COLUMN_PRODUCT_ID = 'product_id';

    // Static getter methods for table and column names
    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnEventId(): string
    {
        return self::COLUMN_EVENT_ID;
    }

    public static function getColumnProductId(): string
    {
        return self::COLUMN_PRODUCT_ID;
    }

    /**
     * Creates the 'event_items' table if it doesn't already exist.
     * @return bool
     */
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnEventId() . " INT NOT NULL,
                " . self::getColumnProductId() . " INT NOT NULL,
                PRIMARY KEY (" . self::getColumnEventId() . ", " . self::getColumnProductId() . "),
                FOREIGN KEY (" . self::getColumnEventId() . ") REFERENCES events(" . EventModel::getColumnId() . ") ON DELETE CASCADE,
                FOREIGN KEY (" . self::getColumnProductId() . ") REFERENCES products(" . ProductModel::getColumnId() . ") ON DELETE CASCADE
            );
        ");
    }


    /**
     * Formats the event item data before insertion or update.
     * @param array $data The input data.
     * @return array The formatted data.
     */
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'event_id' => isset($data['event_id']) ? trim($data['event_id']) : null,
            'product_id' => isset($data['product_id']) ? trim($data['product_id']) : null,
        ];

        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
?>