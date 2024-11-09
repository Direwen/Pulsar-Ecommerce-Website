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
    private const COLUMN_LAST_UPDATED = 'last_updated_at';

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

    public function validateFormData(array $post_data): ?bool
    {
        $errors = [];

        // Validate 'code' - required, must be a string, and max length of 50 characters
        if (empty($post_data[$this->getColumnCode()])) {
            $errors[] = "Inventory code is required.";
        } elseif (strlen($post_data[$this->getColumnCode()]) > 50) {
            $errors[] = "Inventory code cannot exceed 50 characters.";
        }

        // Validate 'variant_id' - required and must be an integer
        if (empty($post_data[$this->getColumnVariantId()]) || !is_numeric($post_data[$this->getColumnVariantId()])) {
            $errors[] = "Variant ID is required and must be a valid integer.";
        }

        // Validate 'stock_quantity' - optional, must be a non-negative integer
        if (isset($post_data[$this->getColumnStockQuantity()])) {
            if (!is_numeric($post_data[$this->getColumnStockQuantity()]) || (int) $post_data[$this->getColumnStockQuantity()] < 0) {
                $errors[] = "Stock quantity must be a non-negative integer.";
            }
        }

        // Validate 'reorder_level' - optional, must be a non-negative integer
        if (isset($post_data[$this->getColumnReorderLevel()])) {
            if (!is_numeric($post_data[$this->getColumnReorderLevel()]) || (int) $post_data[$this->getColumnReorderLevel()] < 0) {
                $errors[] = "Reorder level must be a non-negative integer.";
            }
        }

        // Check if there are any validation errors
        if (!empty($errors)) {
            setMessage(implode(", ", $errors), 'error');
            return false;
        }

        // Return true if validation passed with no errors
        return true;
    }


    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'code' => isset($data['code']) ? strtoupper(trim($data['code'])) : null,
            'variant_id' => $data['variant_id'] ?? null,
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'reorder_level' => $data['reorder_level'] ?? 0,
        ];

        // Filter out null values to keep only the provided attributes
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }

}
