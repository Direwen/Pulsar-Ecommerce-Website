<?php

require_once "./models/baseModel.php";

class OrderModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_USER_ID = 'user_id';
    private const COLUMN_STATUS = 'status';
    private const COLUMN_TOTAL_PRICE = 'total_price';
    private const COLUMN_USED_DISCOUNT_ID = 'used_discount_id';
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const COLUMN_FIRST_NAME = 'first_name';
    private const COLUMN_LAST_NAME = 'last_name';
    private const COLUMN_COMPANY = 'company';
    private const COLUMN_ADDRESS = 'address';
    private const COLUMN_APARTMENT = 'apartment';
    private const COLUMN_POSTAL_CODE = 'postal_code';
    private const COLUMN_CITY = 'city';
    private const COLUMN_COUNTRY = 'country';
    private const COLUMN_PHONE = 'phone';
    private const COLUMN_ORDER_CODE = 'order_code';
    private const COLUMN_IS_REFUNDED = 'is_refunded';
    private const TABLE_NAME = 'orders';

    // Getter methods for column names
    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnUserId(): string
    {
        return self::COLUMN_USER_ID;
    }

    public static function getColumnStatus(): string
    {
        return self::COLUMN_STATUS;
    }

    public static function getColumnTotalPrice(): string
    {
        return self::COLUMN_TOTAL_PRICE;
    }

    public static function getColumnUsedDiscountId(): string
    {
        return self::COLUMN_USED_DISCOUNT_ID;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnUpdatedAt(): string
    {
        return self::COLUMN_UPDATED_AT;
    }

    public static function getColumnFirstName(): string
    {
        return self::COLUMN_FIRST_NAME;
    }

    public static function getColumnLastName(): string
    {
        return self::COLUMN_LAST_NAME;
    }

    public static function getColumnCompany(): string
    {
        return self::COLUMN_COMPANY;
    }

    public static function getColumnAddress(): string
    {
        return self::COLUMN_ADDRESS;
    }

    public static function getColumnApartment(): string
    {
        return self::COLUMN_APARTMENT;
    }

    public static function getColumnPostalCode(): string
    {
        return self::COLUMN_POSTAL_CODE;
    }

    public static function getColumnCity(): string
    {
        return self::COLUMN_CITY;
    }

    public static function getColumnCountry(): string
    {
        return self::COLUMN_COUNTRY;
    }

    public static function getColumnPhone(): string
    {
        return self::COLUMN_PHONE;
    }

    public static function getColumnOrderCode(): string
    {
        return self::COLUMN_ORDER_CODE;
    }

    public static function getColumnIsRefunded(): string
    {
        return self::COLUMN_IS_REFUNDED;
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
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnOrderCode() . " VARCHAR(50) NOT NULL UNIQUE,
                " . self::getColumnUserId() . " INT NULL,
                " . self::getColumnStatus() . " ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
                " . self::getColumnTotalPrice() . " DECIMAL(10, 2) NOT NULL,
                " . self::getColumnUsedDiscountId() . " INT NULL,
                " . self::getColumnFirstName() . " VARCHAR(100) NOT NULL,
                " . self::getColumnLastName() . " VARCHAR(100) NOT NULL,
                " . self::getColumnCompany() . " VARCHAR(255) NULL,
                " . self::getColumnAddress() . " VARCHAR(255) NOT NULL,
                " . self::getColumnApartment() . " VARCHAR(255) NULL,
                " . self::getColumnPostalCode() . " VARCHAR(20) NOT NULL,
                " . self::getColumnCity() . " VARCHAR(100) NOT NULL,
                " . self::getColumnCountry() . " VARCHAR(100) NOT NULL,
                " . self::getColumnPhone() . " VARCHAR(20) NOT NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                " . self::getColumnIsRefunded() . " BOOLEAN NOT NULL DEFAULT FALSE,
                CONSTRAINT FOREIGN KEY (" . self::getColumnUserId() . ") REFERENCES " . UserModel::getTableName() . "(" . UserModel::getColumnId() . ") ON DELETE SET NULL,
                CONSTRAINT FOREIGN KEY (" . self::getColumnUsedDiscountId() . ") REFERENCES " . DiscountModel::getTableName() . "(" . DiscountModel::getColumnId() . ") ON DELETE SET NULL,
                INDEX (" . self::getColumnUserId() . "),
                INDEX (" . self::getColumnUsedDiscountId() . "),
                INDEX (" . self::getColumnOrderCode() . ")
            );
        ");
    }

    // Format data for storing in the database
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'user_id' => $data['user_id'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'total_price' => $data['total_price'] ?? null,
            'used_discount_id' => $data['used_discount_id'] ?? null,
            'first_name' => trim(ucwords($data['first_name'])) ?? null,
            'last_name' => trim(ucwords($data['last_name'])) ?? null,
            'company' => trim(ucwords($data['company'])) ?? null,
            'address' => trim(ucwords($data['address'])) ?? null,
            'apartment' => trim(ucwords($data['apartment'])) ?? null,
            'postal_code' => strtoupper($data['postal_code']) ?? null,
            'city' => trim(ucwords($data['city'])) ?? null,
            'country' => trim(strtoupper($data['country'])) ?? null,
            'phone' => $data['phone'] ?? null,
            'order_code' => $data['order_code'] ?? $this->generateOrderCode(),
            'is_refunded' => $data['is_refunded'] ?? false,
        ];

        // Filter out null values if needed
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }

    public function generateOrderCode()
    {
        return uniqid("ORD") . time();
    }

    public function validateFormData(array $post_data): ?bool
    {
        $errors = [];

        // Validate 'user_id' - required and must be an integer
        if (empty($post_data[$this->getColumnUserId()]) || !is_numeric($post_data[$this->getColumnUserId()])) {
            $errors[] = "User ID is required and must be a valid integer.";
        }

        // Validate 'total_price' - required, must be a valid number and positive
        if (empty($post_data[$this->getColumnTotalPrice()]) || !is_numeric($post_data[$this->getColumnTotalPrice()]) || $post_data[$this->getColumnTotalPrice()] <= 0) {
            $errors[] = "Total price is required, must be a valid number, and greater than zero.";
        }

        // Validate 'used_discount_id' - optional, but must be a valid integer if provided
        if (!empty($post_data[$this->getColumnUsedDiscountId()]) && !is_numeric($post_data[$this->getColumnUsedDiscountId()])) {
            $errors[] = "Discount ID must be a valid integer.";
        }

        // Validate delivery fields
        $delivery_fields = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'address' => 'Address',
            'postal_code' => 'Postal code',
            'city' => 'City',
            'country' => 'Country',
            'phone' => 'Phone'
        ];

        foreach ($delivery_fields as $field => $label) {
            if (empty($post_data[$field])) {
                $errors[] = "$label is required.";
            }
        }

        // Validate 'order_code' - required, must be unique and <= 50 characters
        if (empty($post_data[$this->getColumnOrderCode()]) || strlen($post_data[$this->getColumnOrderCode()]) > 50) {
            $errors[] = "Order code is required and must not exceed 50 characters.";
        }

        // Additional validation for phone format (example regex)
        if (!empty($post_data['phone']) && !preg_match('/^\+?[0-9]{10,15}$/', $post_data['phone'])) {
            $errors[] = "Phone number must be valid and between 10 to 15 digits.";
        }

        // Return errors if any
        if (!empty($errors)) {
            setMessage(implode(", ", $errors), 'error');
            return false;
        }

        // Return true if no errors
        return true;
    }


}
