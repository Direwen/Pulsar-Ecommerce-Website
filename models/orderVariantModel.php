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

    public function getTotalProductsSold()
    {
        global $order_model;

        $sold_out_products_counts = [];
        $page = 1; // Start at page 1

        do {
            $fetched_orders = ErrorHandler::handle(fn() => $this->getAll(
                aggregates: [
                    ["column" => $this->getColumnQuantity(), "function" => "SUM", "alias" => "products_count", "table" => $this->getTableName()],
                ],
                joins: [
                    [
                        'type' => "INNER JOIN",
                        'table' => $order_model->getTableName(),
                        'on' => "order_variants.order_id = orders.id"
                    ]
                ],
                conditions: [
                    [
                        'attribute' => $order_model->getTableName() . '.' . $order_model->getColumnStatus(),
                        'operator' => "<>",
                        'value' => "cancelled",
                    ]
                ],
                page: $page // Use the current page
            ));

            if (!empty($fetched_orders["records"])) $sold_out_products_counts[] = $fetched_orders["records"][0]["products_count"];

            $page++; // Increment page after processing
        } while ($fetched_orders["hasMore"]);

        return array_sum($sold_out_products_counts);
    }


    public function getTopSellingProducts()
    {
        global $order_model, $product_model, $variant_model;

        $products = [];
        $page = 1; // Start at page 1

        do {
            // Fetch records for the current page
            $fetched_records = ErrorHandler::handle(fn() => $this->getAll(
                select: [
                    ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()]
                ],
                aggregates: [
                    ["column" => $this->getColumnQuantity(), "function" => "SUM", "alias" => "total_sold", "table" => $this->getTableName()],
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
                        'type' => "INNER JOIN",
                        'table' => $product_model->getTableName(),
                        'on' => "variants.product_id = products.id"
                    ],
                ],
                groupBy: $product_model->getTableName() . "." . $product_model->getColumnId(),
                conditions: [],
                page: $page // Use the current page
            ));

            foreach ($fetched_records["records"] as $record) {
                $productName = $record["product_name"];
                $totalSold = $record["total_sold"];

                if (isset($products[$productName])) {
                    $products[$productName] += $totalSold; // Aggregate the total sold
                } else {
                    $products[$productName] = $totalSold; // Initialize if not set
                }
            }

            $page++; // Increment the page after processing
        } while ($fetched_records["hasMore"]);

        // Convert the result to the desired array format
        return array_map(
            fn($productName, $totalSold) => ["product_name" => $productName, "total_sold" => $totalSold],
            array_keys($products),
            $products
        );
    }


}
?>