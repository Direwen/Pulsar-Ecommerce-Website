<?php

require_once "./models/baseModel.php";

class EventModel extends BaseModel
{
    // Table and column constants
    public const TABLE_NAME = 'events';

    public const COLUMN_ID = 'id';
    public const COLUMN_CODE = 'code';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_BANNER_IMG = 'banner_img';
    public const COLUMN_START_AT = 'start_at';
    public const COLUMN_END_AT = 'end_at';
    public const COLUMN_DISCOUNT = 'discount'; // New discount column
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';

    // Static getter methods for table and column names
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

    public static function getColumnName(): string
    {
        return self::COLUMN_NAME;
    }

    public static function getColumnDescription(): string
    {
        return self::COLUMN_DESCRIPTION;
    }

    public static function getColumnBannerImg(): string
    {
        return self::COLUMN_BANNER_IMG;
    }

    public static function getColumnStartAt(): string
    {
        return self::COLUMN_START_AT;
    }

    public static function getColumnEndAt(): string
    {
        return self::COLUMN_END_AT;
    }

    public static function getColumnDiscount(): string
    {
        return self::COLUMN_DISCOUNT; // New discount getter
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnUpdatedAt(): string
    {
        return self::COLUMN_UPDATED_AT;
    }

    /**
     * Creates the 'events' table if it doesn't already exist.
     * @return bool
     */
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnCode() . " VARCHAR(20) NOT NULL UNIQUE,
                " . self::getColumnName() . " VARCHAR(255) NOT NULL,
                " . self::getColumnDescription() . " TEXT NULL,
                " . self::getColumnBannerImg() . " VARCHAR(255),
                " . self::getColumnStartAt() . " TIMESTAMP NOT NULL,
                " . self::getColumnEndAt() . " TIMESTAMP NULL,
                " . self::getColumnDiscount() . " INT NOT NULL, -- New discount column
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ");
    }

    /**
     * Validates the form data for creating or updating events.
     * @param array $post_data The submitted form data.
     * @param array $files_data The uploaded file data (if any).
     * @return bool|null Returns true if validation passes, false if it fails, or null if errors occur.
     */
    public function validateFormData(array $post_data, array $files_data = [], bool $check_img = true): ?bool
    {
        $errors = [];

        // Validate 'name' - required and max length of 255 characters
        if (empty($post_data[self::getColumnName()])) {
            $errors[] = "Event name is required.";
        } elseif (strlen($post_data[self::getColumnName()]) > 255) {
            $errors[] = "Event name cannot exceed 255 characters.";
        }

        // Validate 'start_at' - required and must be a valid timestamp
        $start_at = $post_data[self::getColumnStartAt()] ?? null;
        $start_at_timestamp = $start_at ? strtotime($start_at) : false;

        if (empty($start_at) || $start_at_timestamp === false) {
            $errors[] = "Start date and time are required and must be valid.";
        }

        // Validate 'end_at' - required and must be a valid timestamp
        $end_at = $post_data[self::getColumnEndAt()] ?? null;
        $end_at_timestamp = $end_at ? strtotime($end_at) : false;

        if (empty($end_at) || $end_at_timestamp === false) {
            $errors[] = "End date and time are required and must be valid.";
        }

        $products = $post_data["products"] ?? [];
        if (empty($products)) {
            $errors[] = "Event Products are required.";
        }

        // Validate discount
        $discount = $post_data[self::getColumnDiscount()] ?? null;
        if (empty($discount) || !is_numeric($discount) || $discount < 1 || $discount > 80) {
            $errors[] = "Discount value must be numeric and between 1% and 80%.";
        }

        // Ensure 'end_at' is not earlier than 'start_at'
        if ($start_at_timestamp !== false && $end_at_timestamp !== false) {
            if ($end_at_timestamp < $start_at_timestamp) {
                $errors[] = "End date and time must not be earlier than the start date and time.";
            } elseif ($end_at_timestamp === $start_at_timestamp) {
                $errors[] = "Start and end date cannot be the same. Please adjust the timings.";
            }
        }

        if ($check_img) {
            if (empty($_FILES["banner_img"]["name"])) {
                $errors[] = "Banner Image is required";
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

    /**
     * Formats the event data before insertion or update.
     * @param array $data The input data.
     * @return array The formatted data.
     */
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'name' => isset($data['name']) ? trim($data['name']) : null,
            'description' => isset($data['description']) ? trim($data['description']) : null,
            'banner_img' => isset($data['banner_img']) ? $data['banner_img'] : null,
            'start_at' => isset($data['start_at']) ? strtotime($data['start_at']) : null,
            'end_at' => isset($data['end_at']) ? strtotime($data['end_at']) : null,
            'code' => isset($data["code"]) ? $data['code'] : null,
            'discount' => isset($data[self::getColumnDiscount()]) ? (int)$data[self::getColumnDiscount()] : null, // Format discount
        ];

        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }

    /**
     * Generates a unique event code for the event.
     * @return string The unique code.
     */
    public function generateEventCode()
    {
        return "EVE". time();
    }

}
