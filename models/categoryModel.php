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
                " . self::getColumnImg() . " VARCHAR(255) NOT NULL,
                INDEX idx_category_name (" . self::getColumnName() . ")
            );
        ");
    }

    public function validateFormData(array $post_data, array $files_data = [], bool $check_img = true): ?bool
    {
        // Validate 'name' - required, unique, max length of 255 characters
        if (empty($post_data['name'])) {
            $errors[] = "Category name is required.";
        } elseif (strlen($post_data['name']) > 255) {
            $errors[] = "Category name cannot exceed 255 characters.";
        }

        // Validate 'software' - optional, should be a valid URL if provided
        if (!empty($post_data['software']) && !filter_var($post_data['software'], FILTER_VALIDATE_URL)) {
            $errors[] = "Software link must be a valid URL.";
        }

        // Validate 'firmware' - optional, should be a valid URL if provided
        if (!empty($post_data['firmware']) && !filter_var($post_data['firmware'], FILTER_VALIDATE_URL)) {
            $errors[] = "Firmware link must be a valid URL.";
        }

        // Validate 'manual' - optional, should be a valid URL if provided
        if (!empty($post_data['manual']) && !filter_var($post_data['manual'], FILTER_VALIDATE_URL)) {
            $errors[] = "Manual link must be a valid URL.";
        }

        if ($check_img) {
            if (empty($files_data["img"]["name"])) {
                $errors[] = "Image is required.";
            }
        }

        // If there are any validation errors, return false and handle the errors
        if (!empty($errors)) {
            setMessage(implode(", ", $errors), 'error');
            return false;
        }

        // Return true if validation passed with no errors
        return true;
    }

    // protected function createRaw($data): bool
    // {
    //     return $this->db->execute(
    //         "INSERT INTO " . self::getTableName() . " 
    //     (" . self::getColumnName() . ", " . self::getColumnSoftware() . ", " . self::getColumnFirmware() . ", " . self::getColumnManual() . ", " . self::getColumnImg() . ")
    //     VALUES (:name, :software, :firmware, :manual, :img)",
    //         [
    //             ':name' => strtolower(trim($data['name'])),
    //             ':software' => !empty($data['software']) ? $data['software'] : null,
    //             ':firmware' => !empty($data['firmware']) ? $data['firmware'] : null,
    //             ':manual' => !empty($data['manual']) ? $data['manual'] : null,
    //             ':img' => $data['img'] ?? null
    //         ]
    //     );
    // }

    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'name' => isset($data['name']) ? strtolower(trim($data['name'])) : null,
            'software' => $data['software'] ?? null,
            'firmware' => $data['firmware'] ?? null,
            'manual' => $data['manual'] ?? null,
            'img' => $data['img'] ?? null
        ];

        // Filter out null values to keep only the provided attributes
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
