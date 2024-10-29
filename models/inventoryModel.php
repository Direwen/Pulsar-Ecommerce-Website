<?php

require_once "./models/baseModel.php";

class InventoryModel extends BaseModel
{
    // Define constants for table and column names
    private const TABLE_NAME = 'inventories';

    private const COLUMN_ID = 'id';
    private const COLUMN_CODE = 'code';
    private const COLUMN_VARIANT_ID = 'variant_id';
    private const COLUMN_STOCK_QUANTITY = 'stock_quantity';
    private const COLUMN_REORDER_LEVEL = 'reorder_level';
    private const COLUMN_LAST_UPDATED = 'last_updated';

    // Static getters for table and column names
    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnCode(): string
    {
        return self::COLUMN_CODE;
    }

    public static function getColumnVariantId(): string
    {
        return self::COLUMN_VARIANT_ID;
    }

    public static function getColumnStockQuantity(): string
    {
        return self::COLUMN_STOCK_QUANTITY;
    }

    public static function getColumnReorderLevel(): string
    {
        return self::COLUMN_REORDER_LEVEL;
    }

    public static function getColumnLastUpdated(): string
    {
        return self::COLUMN_LAST_UPDATED;
    }

    /**
     * Creates the 'inventories' table if it doesn't already exist.
     * @return bool
     */
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnCode() . " VARCHAR(50) NOT NULL UNIQUE,
                " . self::getColumnVariantId() . " INT NOT NULL,
                " . self::getColumnStockQuantity() . " INT DEFAULT 0,
                " . self::getColumnReorderLevel() . " INT DEFAULT 0,
                " . self::getColumnLastUpdated() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (" . self::getColumnVariantId() . ") REFERENCES variants(id) ON DELETE CASCADE,
                INDEX idx_variant_id (" . self::getColumnVariantId() . "),
                INDEX idx_code (" . self::getColumnCode() . ")
            );
        ");
    }


    /**
     * Inserts a new record into the 'inventories' table.
     * @param array $data
     * @return bool
     */
    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " 
            (" . self::getColumnCode() . ", " . self::getColumnVariantId() . ", " . self::getColumnStockQuantity() . ", " . self::getColumnReorderLevel() . ") 
            VALUES (:code, :variant_id, :stock_quantity, :reorder_level)",
            [
                ':code' => strtoupper(trim($data['code'])),  // Ensuring 'code' is formatted in uppercase
                ':variant_id' => $data['variant_id'],
                ':stock_quantity' => $data['stock_quantity'] ?? 0,
                ':reorder_level' => $data['reorder_level'] ?? 0
            ]
        );
    }
}
