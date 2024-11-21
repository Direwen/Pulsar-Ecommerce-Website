<?php

require_once "./models/baseModel.php";

class ReviewModel extends BaseModel
{
    // Define constants for column names
    private const COLUMN_ID = 'id';
    private const COLUMN_USER_ID = 'user_id';
    private const COLUMN_VARIANT_ID = 'variant_id';
    private const COLUMN_RATING = 'rating';
    private const COLUMN_CREATED_AT = 'created_at';
    private const COLUMN_UPDATED_AT = 'updated_at';
    private const TABLE_NAME = 'reviews';

    // Column accessors
    public static function getColumnId(): string { return self::COLUMN_ID; }
    public static function getColumnUserId(): string { return self::COLUMN_USER_ID; }
    public static function getColumnVariantId(): string { return self::COLUMN_VARIANT_ID; }
    public static function getColumnRating(): string { return self::COLUMN_RATING; }
    public static function getColumnCreatedAt(): string { return self::COLUMN_CREATED_AT; }
    public static function getColumnUpdatedAt(): string { return self::COLUMN_UPDATED_AT; }
    public static function getTableName(): string { return self::TABLE_NAME; }

    /**
     * Creates the table with the simplified design.
     */
    public function createTable(): bool
    {
        return $this->db->execute("
            CREATE TABLE IF NOT EXISTS " . self::getTableName() . " (
                " . self::getColumnId() . " INT AUTO_INCREMENT PRIMARY KEY,
                " . self::getColumnUserId() . " INT NULL,
                " . self::getColumnVariantId() . " INT NOT NULL,
                " . self::getColumnRating() . " INT NOT NULL CHECK (" . self::getColumnRating() . " BETWEEN 1 AND 5),
                " . self::getColumnCreatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                " . self::getColumnUpdatedAt() . " TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE (" . self::getColumnUserId() . ", " . self::getColumnVariantId() . "),
                FOREIGN KEY (" . self::getColumnUserId() . ") REFERENCES " . UserModel::getTableName() . "(" . UserModel::getColumnId() . ") ON DELETE SET NULL,
                FOREIGN KEY (" . self::getColumnVariantId() . ") REFERENCES " . VariantModel::getTableName() . "(" . VariantModel::getColumnId() . ") ON DELETE CASCADE
            );
        ");
    }

    /**
     * Formats data for insertion or updating.
     */
    protected function formatData($data, $null_filter = false): array
    {
        $formattedData = [
            'user_id' => $data['user_id'] ?? null,
            'variant_id' => $data['variant_id'] ?? null,
            'rating' => isset($data['rating']) ? $data['rating'] : null,
        ];

        // Filter out null values to keep only the provided attributes
        return $null_filter ? array_filter($formattedData, fn($value) => $value !== null) : $formattedData;
    }

    public function getUnreviewedVariants()
    {
        global $order_variant_model, $product_model, $variant_model, $order_model;
        $page= 1;
        $result = [];
        do {

            $fetched_result = ErrorHandler::handle(fn () => $order_variant_model->getAll(
                select: [
                    [
                        "column" => $variant_model->getColumnId(),
                        "alias" => "id",
                        "table" => $variant_model->getTableName()
                    ],
                    [
                      "column" => $product_model->getColumnName(),
                      "alias" => "product_name",
                      "table" => $product_model->getTableName()  
                    ],
                    [
                        "column" => $variant_model->getColumnName(),
                        "alias" => "name",
                        "table" => $variant_model->getTableName()
                    ],
                    [
                        "column" => $variant_model->getColumnImg(),
                        "alias" => "img",
                        "table" => $variant_model->getTableName()
                    ],
                    [
                        "column" => $this->getColumnId(),
                        "alias" => "review_id",
                        "table" => $this->getTableName()
                    ],
                ],
                joins: [
                    [
                        'type' => "INNER JOIN",
                        'table' => $order_model->getTableName(),
                        'on' => "order_variants.order_id = orders.id"
                    ],
                    [
                        'type' => "INNER JOIN",
                        'table' => $variant_model->getTableName(),
                        'on' => "order_variants.variant_id = variants.id"
                    ],
                    [
                        'type' => "LEFT JOIN",
                        'table' => $this->getTableName(),
                        'on' => "reviews.variant_id = variants.id"
                    ],
                    [
                        'type' => 'INNER JOIN',
                        'table' => $product_model->getTableName(),
                        'on' => "variants.product_id = products.id"
                    ]
                ],
                conditions: [
                    [
                        'attribute' => $order_model->getTableName() . '.' . $order_model->getColumnStatus(),
                        'operator' => "=",
                        'value' => "delivered",
                    ],
                    [
                        'attribute' => $order_model->getTableName() . '.' . $order_model->getColumnUserId(),
                        'operator' => "=",
                        'value' => $_SESSION["user_id"]
                    ],
                    [
                        'attribute' => $this->getTableName() . '.' . $this->getColumnVariantId(),
                        'operator' => "IS",
                        'value' => null
                    ]
                ],
                page: $page

            ));

            $result = array_merge($result, $fetched_result["records"]);
            $page++;

        } while ($fetched_result["hasMore"]);


        return $result;

    }
}
