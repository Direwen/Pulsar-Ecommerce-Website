<?php

require_once "./models/baseModel.php";

class CategoryModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_NAME = 'name';
    private const COLUMN_IMAGE_PATH = 'image_path'; // New column for category image
    private const TABLE_NAME = 'categories';

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnName(): string
    {
        return self::COLUMN_NAME;
    }

    public static function getColumnImagePath(): string
    {
        return self::COLUMN_IMAGE_PATH;
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
                " . self::getColumnName() . " VARCHAR(255) NOT NULL UNIQUE,
                " . self::getColumnImagePath() . " VARCHAR(255) NULL,
                INDEX idx_category_name (" . self::getColumnName() . ")
            );
        ");
    }

    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " (" . self::getColumnName() . ", " . self::getColumnImagePath() . ") VALUES (:name, :image_path)",
            [
                ':name' => strtolower(trim($data['name'])),
                ':image_path' => $data['image_path'] ?? null
            ]
        );
    }
}
