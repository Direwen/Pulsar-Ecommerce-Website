<?php

require_once "./models/baseModel.php";

class InventoryModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_INVENTORY_ID = 'inventory_id';
    private const COLUMN_VARIANT_ID = 'variant_id';
    private const COLUMN_STOCK_QUANTITY = 'stock_quantity';
    private const COLUMN_CREATED_AT = 'created_at';
    private const TABLE_NAME = 'inventories';

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnInventoryId(): string
    {
        return self::COLUMN_INVENTORY_ID;
    }

    public static function getColumnVariantId(): string
    {
        return self::COLUMN_VARIANT_ID;
    }

    public static function getColumnStockQuantity(): string
    {
        return self::COLUMN_STOCK_QUANTITY;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getTableName(): string // New getter for table name
    {
        return self::TABLE_NAME;
    }

    public function createTable(): bool
    {
        return $this->db->execute(" 
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " ( 
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnInventoryId() . " VARCHAR(50) NOT NULL UNIQUE,
                " . self::getColumnVariantId() . " INT NOT NULL,
                " . self::getColumnStockQuantity() . " INT DEFAULT 0,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (" . self::getColumnVariantId() . ") REFERENCES variants(id),
                INDEX idx_variant_id (" . self::getColumnVariantId() . ")
            );
        ");
    }

    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " (" . self::getColumnInventoryId() . ", " . self::getColumnVariantId() . ", " . self::getColumnStockQuantity() . ") VALUES (:inventory_id, :variant_id, :stock_quantity)",
            [
                ':inventory_id' => strtoupper(trim($data['inventory_id'])), // Inventory ID can be formatted as needed
                ':variant_id' => $data['variant_id'],
                ':stock_quantity' => $data['stock_quantity'] ?? 0
            ]
        );
    }
}
