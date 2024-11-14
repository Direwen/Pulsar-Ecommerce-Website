<?php

require_once "./models/baseModel.php";

class AddressModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_USER_ID = 'user_id';
    private const COLUMN_FIRST_NAME = 'first_name';
    private const COLUMN_LAST_NAME = 'last_name';
    private const COLUMN_COMPANY = 'company';
    private const COLUMN_ADDRESS = 'address';
    private const COLUMN_APARTMENT = 'apartment';
    private const COLUMN_POSTAL_CODE = 'postal_code';
    private const COLUMN_CITY = 'city';
    private const COLUMN_PHONE = 'phone';
    private const COLUMN_COUNTRY = 'country';
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const TABLE_NAME = 'addresses';

    // Getter methods for column names
    public static function getColumnId(): string
    {
        return self::COLUMN_ID;
    }

    public static function getColumnUserId(): string
    {
        return self::COLUMN_USER_ID;
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

    public static function getColumnPhone(): string
    {
        return self::COLUMN_PHONE;
    }

    public static function getColumnCountry(): string
    {
        return self::COLUMN_COUNTRY;
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

    // Create table if it doesn't exist
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnUserId() . " INT NOT NULL,
                " . self::getColumnFirstName() . " VARCHAR(255) NOT NULL,
                " . self::getColumnLastName() . " VARCHAR(255) NOT NULL,
                " . self::getColumnCompany() . " VARCHAR(255) DEFAULT NULL,
                " . self::getColumnAddress() . " VARCHAR(255) NOT NULL,
                " . self::getColumnApartment() . " VARCHAR(255) DEFAULT NULL,
                " . self::getColumnPostalCode() . " VARCHAR(20) NOT NULL,
                " . self::getColumnCity() . " VARCHAR(255) NOT NULL,
                " . self::getColumnPhone() . " VARCHAR(30) NOT NULL,
                " . self::getColumnCountry() . " VARCHAR(100) NOT NULL,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_user_id (" . self::getColumnUserId() . ")
            );
        ");
    }

    // Format data for storing in the database
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'user_id' => $data['user_id'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'company' => $data['company'] ?? null,
            'address' => $data['address'] ?? null,
            'apartment' => $data['apartment'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
        ];

        // Filter out null values if needed
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
