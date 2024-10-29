<?php

require_once "./models/baseModel.php";

class VariantModel extends BaseModel
{
    // Table and column constants
    public const TABLE_NAME = 'variants';

    public const COLUMN_ID = 'id';
    public const COLUMN_PRODUCT_ID = 'product_id';
    public const COLUMN_TYPE = 'type';
    public const COLUMN_NAME = 'name';
    public const COLUMN_UNIT_PRICE = 'unit_price';
    public const COLUMN_IMG = 'img';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';

    // Static getter methods for table and column names
    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function getColumnId(): string {
        return self::COLUMN_ID;
    }

    public static function getColumnProductId(): string {
        return self::COLUMN_PRODUCT_ID;
    }

    public static function getColumnType(): string {
        return self::COLUMN_TYPE;
    }

    public static function getColumnName(): string {
        return self::COLUMN_NAME;
    }

    public static function getColumnUnitPrice(): string {
        return self::COLUMN_UNIT_PRICE;
    }

    public static function getColumnImg(): string {
        return self::COLUMN_IMG;
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
                " . self::getColumnType() . " VARCHAR(255) NOT NULL,
                " . self::getColumnName() . " VARCHAR(255) NOT NULL,
                " . self::getColumnUnitPrice() . " DECIMAL(10, 2) NOT NULL,
                " . self::getColumnImg() . " VARCHAR(255) NOT NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (" . self::getColumnProductId() . ") REFERENCES " . ProductModel::getTableName() . "(" . ProductModel::getColumnId() . ") ON DELETE CASCADE
            );
        ");
    }

    /**
     * Inserts a new record into the 'variants' table.
     * @param array $data
     * @return bool
     */
    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " 
            (" . self::getColumnProductId() . ", " . self::getColumnType() . ", " . self::getColumnName() . ", " . self::getColumnUnitPrice() . ", " . self::getColumnImg() . ")
            VALUES (:product_id, :type, :name, :unit_price, :img)",
            [
                ':product_id' => $data['product_id'],
                ':type' => strtolower(trim($data['type'])), // Standardize variant type
                ':name' => strtolower(trim($data['name'])), // Standardize name
                ':unit_price' => $data['unit_price'],
                ':img' => $data['img']
            ]
        );
    }
}
