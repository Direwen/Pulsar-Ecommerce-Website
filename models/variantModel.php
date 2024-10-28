<?php

require_once "./models/baseModel.php";

class VariantModel extends BaseModel
{
    // Table and column constants
    public const TABLE_NAME = 'variants';

    public const COLUMN_ID = 'id';
    public const COLUMN_PRODUCT_ID = 'product_id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_UNIT_PRICE = 'unit_price';
    public const COLUMN_SKU = 'sku';
    public const COLUMN_IMAGE_PATH = 'image_path';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function getColumnId(): string {
        return self::COLUMN_ID;
    }

    public static function getColumnProductId(): string {
        return self::COLUMN_PRODUCT_ID;
    }

    public static function getColumnName(): string {
        return self::COLUMN_NAME;
    }

    public static function getColumnUnitPrice(): string {
        return self::COLUMN_UNIT_PRICE;
    }

    public static function getColumnSku(): string {
        return self::COLUMN_SKU;
    }

    public static function getColumnImagePath(): string {
        return self::COLUMN_IMAGE_PATH;
    }

    public static function getColumnCreatedAt(): string {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnUpdatedAt(): string {
        return self::COLUMN_UPDATED_AT;
    }

    
    /**
     * Creates the 'variants' table if it doesn't already exist.
     * @return bool
     */
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnProductId() . " INT NOT NULL,
                " . self::getColumnName() . " VARCHAR(255) NOT NULL,
                " . self::getColumnUnitPrice() . " DECIMAL(10, 2) NOT NULL,
                " . self::getColumnSku() . " VARCHAR(255) NOT NULL UNIQUE,
                " . self::getColumnImagePath() . " VARCHAR(255),
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (" . self::getColumnProductId() . ") REFERENCES " . ProductModel::getTableName() . "(" . ProductModel::getColumnId() . ") ON DELETE CASCADE
            );
        ");
    }

    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " (" . self::getColumnName() . ", " . self::getColumnProductId() . ", " . self::getColumnUnitPrice() . ", " . self::getColumnSku() . ", " . self::getColumnImagePath() . ") VALUES (:name, :product_id, :unit_price, :sku, :image_path)",
            [
                ':name' => strtolower(trim($data['name'])),
                ':product_id' => $data['product_id'],
                ':unit_price' => $data['unit_price'],
                ':sku' => $data['sku'],
                ':image_path' => $data['image_path'] ?? null, // Optional field
            ]
        );
    }
}
