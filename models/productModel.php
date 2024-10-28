<?php

require_once "./models/baseModel.php";

class ProductModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_NAME = 'name';
    private const COLUMN_CATEGORY_ID = 'category_id'; // Foreign key to categories
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const TABLE_NAME = 'products';

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnName(): string
    {
        return self::COLUMN_NAME;
    }

    public static function getColumnCategoryId(): string
    {
        return self::COLUMN_CATEGORY_ID;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnUpdatedAt(): string
    {
        return self::COLUMN_UPDATED_AT;
    }

    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnName() . " VARCHAR(255) NOT NULL,
                " . self::getColumnCategoryId() . " INT NOT NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (" . self::getColumnCategoryId() . ") REFERENCES categories(" . CategoryModel::getColumnId() . ") ON DELETE CASCADE,
                INDEX idx_product_name (" . self::getColumnName() . ")
            );
        ");
    }

    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " (" . self::getColumnName() . ", " . self::getColumnCategoryId() . ") VALUES (:name, :category_id)",
            [
                ':name' => strtolower(trim($data['name'])),
                ':category_id' => $data['category_id'],
            ]
        );
    }
}
