<?php

require_once "./models/baseModel.php";

class OrderVariantModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ORDER_ID = 'order_id';
    private const COLUMN_VARIANT_ID = 'variant_id';
    private const COLUMN_QUANTITY = 'quantity';
    private const COLUMN_PRICE_AT_ORDER = 'price_at_order';
    private const TABLE_NAME = 'order_variants';

    // Getter methods for column names
    public static function getColumnOrderId(): string
    {
        return self::COLUMN_ORDER_ID;
    }

    public static function getColumnVariantId(): string
    {
        return self::COLUMN_VARIANT_ID;
    }

    public static function getColumnQuantity(): string
    {
        return self::COLUMN_QUANTITY;
    }

    public static function getColumnPriceAtOrder(): string
    {
        return self::COLUMN_PRICE_AT_ORDER;
    }

    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    // Create table if it doesn't exist
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnOrderId() . " INT NOT NULL,
                " . self::getColumnVariantId() . " INT NOT NULL,
                " . self::getColumnQuantity() . " INT NOT NULL,
                " . self::getColumnPriceAtOrder() . " DECIMAL(10, 2) NOT NULL,
                PRIMARY KEY (" . self::getColumnOrderId() . ", " . self::getColumnVariantId() . "),
                CONSTRAINT FOREIGN KEY (" . self::getColumnOrderId() . ") REFERENCES " . OrderModel::getTableName() . "(" . OrderModel::getColumnId() . ") ON DELETE CASCADE,
                CONSTRAINT FOREIGN KEY (" . self::getColumnVariantId() . ") REFERENCES " . VariantModel::getTableName() . "(" . VariantModel::getColumnId() . ") ON DELETE CASCADE
            );
        ");
    }

    // Format data for storing in the database
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'order_id' => $data['order_id'] ?? null,
            'variant_id' => $data['variant_id'] ?? null,
            'quantity' => $data['quantity'] ?? null,
            'price_at_order' => $data['price_at_order'] ?? null,
        ];

        // Filter out null values if needed
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
?>
