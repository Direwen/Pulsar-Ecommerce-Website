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
    private const COLUMN_IMPORTANT_FEATURES = 'important_features'; // JSON type
    private const COLUMN_REQUIREMENT = 'requirement';
    private const COLUMN_PACKAGE_CONTENT = 'package_content';
    private const COLUMN_IMG_FOR_ADS = 'img_for_ads';
    private const COLUMN_IMG = 'img';
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

    public static function getImportantFeatures(): string
    {
        return self::COLUMN_IMPORTANT_FEATURES;
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
                " . self::getColumnDimension() . " VARCHAR(255) NOT NULL,
                " . self::getColumnFeature() . " JSON NOT NULL,
                " . self::getImportantFeatures() . " JSON NOT NULL,
                " . self::getColumnRequirement() . " VARCHAR(255) NULL,
                " . self::getColumnPackageContent() . " TEXT NOT NULL,
                " . self::getColumnImgForAds() . " JSON NOT NULL,
                " . self::getColumnImg() . " VARCHAR(255) NOT NULL,
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
            "INSERT INTO " . self::getTableName() . " 
            (" . self::getColumnName() . ", " . self::getColumnCategoryId() . ", " . self::getColumnDescription() . ", " . self::getColumnDimension() . ", " . self::getColumnFeature() . ", " . self::getImportantFeatures() . ", " . self::getColumnRequirement() . ", " . self::getColumnPackageContent() . ", " . self::getColumnImgForAds() . ", " . self::getColumnImg() . ")
            VALUES (:name, :category_id, :details_description, :dimension, :feature, :important_features, :requirement, :package_content, :img_for_ads, :img)",
            [
                ':name' => strtolower(trim($data['name'])),
                ':category_id' => $data['category_id'],
                ':details_description' => $data['details_description'] ?? null,
                ':dimension' => $data['dimension'] ?? null,
                ':feature' => json_encode($data['feature'] ?? null),
                ':important_features' => json_encode($data['important_features'] ?? []),  // Encode important features as JSON
                ':requirement' => $data['requirement'] ?? null,
                ':package_content' => $data['package_content'] ?? null,
                ':img_for_ads' => json_encode($data['img_for_ads'] ?? null),
                ':img' => $data['img'] ?? null
            ]
        );
    }
}
