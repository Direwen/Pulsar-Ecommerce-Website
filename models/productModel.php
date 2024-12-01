<?php

require_once "./models/baseModel.php";

class ProductModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_NAME = 'name';
    private const COLUMN_CATEGORY_ID = 'category_id'; // Foreign key to categories
    private const COLUMN_DESCRIPTION = 'description';
    private const COLUMN_DIMENSION = 'dimension';
    private const COLUMN_FEATURE = 'feature';
    private const COLUMN_IMPORTANT_FEATURE = 'important_feature'; // JSON type
    private const COLUMN_REQUIREMENT = 'requirement';
    private const COLUMN_PACKAGE_CONTENT = 'package_content';
    private const COLUMN_IMG_FOR_ADS = 'img_for_ads';
    private const COLUMN_IMG = 'img';
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const COLUMN_VIEWS = 'views'; // Add the views column
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

    public static function getColumnDescription(): string
    {
        return self::COLUMN_DESCRIPTION;
    }

    public static function getColumnDimension(): string
    {
        return self::COLUMN_DIMENSION;
    }

    public static function getColumnFeature(): string
    {
        return self::COLUMN_FEATURE;
    }

    public static function getColumnImportantFeature(): string
    {
        return self::COLUMN_IMPORTANT_FEATURE;
    }

    public static function getColumnRequirement(): string
    {
        return self::COLUMN_REQUIREMENT;
    }

    public static function getColumnPackageContent(): string
    {
        return self::COLUMN_PACKAGE_CONTENT;
    }

    public static function getColumnImgForAds(): string
    {
        return self::COLUMN_IMG_FOR_ADS;
    }

    public static function getColumnImg(): string
    {
        return self::COLUMN_IMG;
    }

    public static function getColumnCreatedAt(): string
    {
        return self::COLUMN_CREATED_AT;
    }

    public static function getColumnUpdatedAt(): string
    {
        return self::COLUMN_UPDATED_AT;
    }
    
    public static function getColumnViews(): string
    {
        return self::COLUMN_VIEWS;
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
                " . self::getColumnDescription() . " TEXT NOT NULL,
                " . self::getColumnDimension() . " JSON NOT NULL,
                " . self::getColumnFeature() . " JSON NOT NULL,
                " . self::getColumnImportantFeature() . " JSON Null,
                " . self::getColumnRequirement() . " JSON NULL,
                " . self::getColumnPackageContent() . " JSON NOT NULL,
                " . self::getColumnImgForAds() . " JSON NOT NULL,
                " . self::getColumnImg() . " VARCHAR(255) NOT NULL,
                " . self::getColumnViews() . " INT DEFAULT 0,
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (" . self::getColumnCategoryId() . ") REFERENCES categories(" . CategoryModel::getColumnId() . ") ON DELETE CASCADE,
                INDEX idx_product_name (" . self::getColumnName() . ")
            );
        ");
    }

    public function validateFormData(array $post_data, array $files_data = [], bool $check_img = true): ?bool
    {
        $errors = [];

        // Validate 'name' - required, max length of 255 characters
        if (empty($post_data[$this->getColumnName()])) {
            $errors[] = "Product name is required.";
        } elseif (strlen($post_data[$this->getColumnName()]) > 255) {
            $errors[] = "Product name cannot exceed 255 characters.";
        }

        // Validate 'category_id' - required, should be an integer
        if (empty($post_data[$this->getColumnCategoryId()]) || !is_numeric($post_data[$this->getColumnCategoryId()])) {
            $errors[] = "Category ID is required and must be a valid integer.";
        }

        // Validate 'description' - required, text field
        if (empty($post_data[$this->getColumnDescription()])) {
            $errors[] = "Product description is required.";
        }

        // Validate 'dimension' fields individually
        if (!empty($post_data['dimension'])) {
            $dimension = $post_data['dimension'];

            // Define realistic limits for each dimension (in cm and kg)
            $limits = [
                'length' => [10, 800],   // Minimum 10 mm, maximum 800 mm
                'width'  => [10, 500],   // Minimum 10 mm, maximum 500 mm
                'height' => [5, 200],    // Minimum 5 mm, maximum 200 mm
                'weight' => [5, 5000]   // Minimum 5 g, maximum 5000 g
            ];            

            // Check if length, width, height, and weight exist and validate them
            foreach ($limits as $field => [$min, $max]) {
                if (isset($dimension[$field])) {
                    // Check if the input is numeric
                    if (!is_numeric($dimension[$field])) {
                        $errors[] = ucfirst($field) . " must be a numeric value.";
                    } else {
                        // Check if the value falls within the specified range
                        if ($dimension[$field] < $min || $dimension[$field] > $max) {
                            $errors[] = ucfirst($field) . " must be between $min and $max.";
                        }
                    }
                } else {
                    $errors[] = ucfirst($field) . " must be provided a value.";
                }
            }
        }

        // Validate JSON fields ('feature', 'important_features', 'requirement', 'package_content')
        if (!empty($post_data[$this->getColumnFeature()]) && !is_array($post_data[$this->getColumnFeature()])) {
            $errors[] = "Features must be provided.";
        }
        if (!is_array($post_data[$this->getColumnImportantFeature()])) {
            $errors[] = "Important features must be provided.";
        }
        if (!is_array($post_data[$this->getColumnRequirement()])) {
            $errors[] = "Requirements must be provided.";
        }
        if (!empty($post_data[$this->getColumnPackageContent()]) && !is_array($post_data[$this->getColumnPackageContent()])) {
            $errors[] = "Package content must be provided.";
        }

        if ($check_img) {

            // Validate 'img_for_ads' - optional but if provided, ensure it's an array (for multiple image handling)
            if (empty($files_data[$this->getColumnImgForAds()]["name"])) {
                $errors[] = "Image for ads must be provided.";
            }

            // Validate 'img' - check if required or optional based on $check_img flag
            if (empty($files_data[$this->getColumnImg()]["name"])) {
                $errors[] = "Main product image is required.";
            }
        }

        // If there are any validation errors, return false and handle the errors
        if (!empty($errors)) {
            var_dump($errors);
            setMessage(implode(", ", $errors), 'error');
            return false;
        }

        // Return true if validation passed with no errors
        return true;
    }

    protected function formatData($data, $null_filter=false): array
    {
        $formattedData = [
            'name' => isset($data['name']) ? strtolower(trim($data['name'])) : null,
            'category_id' => $data['category_id'] ?? null,
            'description' => $data['description'] ?? null,
            'dimension' => isset($data['dimension']) ? json_encode(array_map(fn($value) => is_numeric($value) ? round($value, 1) : $value, $data['dimension'])) : null,
            'feature' => isset($data['feature']) ? json_encode($data['feature']) : null,
            'important_feature' => isset($data['important_feature']) ? json_encode($data['important_feature']) : null,
            'requirement' => isset($data['requirement']) ? json_encode($data['requirement']) : null,
            'package_content' => isset($data['package_content']) ? json_encode($data['package_content']) : null,
            'img_for_ads' => isset($data['img_for_ads']) ? json_encode($data['img_for_ads']) : null,
            'img' => $data['img'] ?? null,
            'views' => isset($data['views']) ? $data['views'] : null, // Default value is 0 for views

        ];

        // Filter out null values to keep only the provided attributes
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }
}
