<?php

require_once "./models/baseModel.php";

class CategoryModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_NAME = 'name';
    private const COLUMN_SOFTWARE = 'software'; // Link for software resources
    private const COLUMN_FIRMWARE = 'firmware'; // Link for firmware resources
    private const COLUMN_MANUAL = 'manual';     // Link for manual resources
    private const COLUMN_IMG = 'img';
    private const TABLE_NAME = 'categories';

    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnName(): string
    {
        return self::COLUMN_NAME;
    }

    public static function getColumnSoftware(): string
    {
        return self::COLUMN_SOFTWARE;
    }

    public static function getColumnFirmware(): string
    {
        return self::COLUMN_FIRMWARE;
    }

    public static function getColumnManual(): string
    {
        return self::COLUMN_MANUAL;
    }

    public static function getColumnImg(): string
    {
        return self::COLUMN_IMG;
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
                " . self::getColumnSoftware() . " VARCHAR(255) NULL,
                " . self::getColumnFirmware() . " VARCHAR(255) NULL,
                " . self::getColumnManual() . " VARCHAR(255) NULL,
                " . self::getColumnImg() . " VARCHAR(255),
                INDEX idx_category_name (" . self::getColumnName() . ")
            );
        ");
    }

    protected function createRaw($data): bool
    {
        return $this->db->execute(
            "INSERT INTO " . self::getTableName() . " 
            (" . self::getColumnName() . ", " . self::getColumnSoftware() . ", " . self::getColumnFirmware() . ", " . self::getColumnManual() . ", " . self::getColumnImg() . ")
            VALUES (:name, :software, :firmware, :manual, :img)",
            [
                ':name' => strtolower(trim($data['name'])),
                ':software' => $data['software'] ?? null,
                ':firmware' => $data['firmware'] ?? null,
                ':manual' => $data['manual'] ?? null,
                ':img' => $data['img'] ?? null
            ]
        );
    }
}
