<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Sanitize and assign POST data directly
$filtered_data = [
    'applied_discount_code' => $_POST['applied_discount_code'] ?? '',
    'delivery' => [
        'country' => $_POST['delivery']['country'] ?? '',
        'first_name' => $_POST['delivery']['first_name'] ?? '',
        'last_name' => $_POST['delivery']['last_name'] ?? '',
        'company' => $_POST['delivery']['company'] ?? '',
        'address' => $_POST['delivery']['address'] ?? '',
        'apartment' => $_POST['delivery']['apartment'] ?? '',
        'postal_code' => $_POST['delivery']['postal_code'] ?? '',
        'city' => $_POST['delivery']['city'] ?? '',
        'phone' => $_POST['delivery']['phone'] ?? '',
        'save_address' => !empty($_POST['delivery']['save_address']),
    ],
    'payment' => [
        'method' => $_POST['payment']['method'] ?? '',
    ],
    'billing' => [
        'same_as_shipping' => $_POST['billing']['same_as_shipping'] ?? '',
        'first_name' => $_POST['billing']['first_name'] ?? '',
        'last_name' => $_POST['billing']['last_name'] ?? '',
        'company' => $_POST['billing']['company'] ?? '',
        'address' => $_POST['billing']['address'] ?? '',
        'apartment' => $_POST['billing']['apartment'] ?? '',
        'postal_code' => $_POST['billing']['postal_code'] ?? '',
        'city' => $_POST['billing']['city'] ?? '',
        'phone' => $_POST['billing']['phone'] ?? '',
    ],
    'user_id' => $_POST['user_id'] ?? '',
    'user_email' => $_POST['user_email'] ?? '',
];

// Wrap the entire order processing in a single transaction
$result = $error_handler->handleDbOperation(function () use ($filtered_data, $mail_service, $user_model, $discount_model, $variant_model, $inventory_model, $order_model, $order_variant_model, $address_model) {
    // Validate user
    $user = $user_model->get(
        conditions: [
            $user_model->getColumnId() => $filtered_data["user_id"]
        ]
    );
    if ($user === true) {
        throw new Exception("User validation failed.");
    }

    // Validate discount code if it's entered
    $discount = null;
    if (!empty($filtered_data["applied_discount_code"])) {
        $discount = $discount_model->get(
            conditions: [
                $discount_model->getColumnCode() => $filtered_data["applied_discount_code"]
            ]
        );
        if ($discount === true || !$discount_model->validateDiscount($discount)) {
            throw new Exception("Invalid discount code.");
        }
    }

    // Get total price
    $cart = json_decode($_COOKIE["CART"], true);
    $selected_variants = [];

    foreach ($cart as $variant_id => $quantity) {
        $variant = $variant_model->get(
            conditions: [
                $variant_model->getColumnId() => $variant_id
            ]
        );

        if (is_array($variant)) {
            $selected_variants[] = ["quantity" => $quantity, ...$variant];

            $inventory = $inventory_model->get(
                conditions: [
                    $inventory_model->getColumnVariantId() => $variant[$variant_model->getColumnId()]
                ]
            );

            if (!is_array($inventory) || $inventory[$inventory_model->getColumnStockQuantity()] < $quantity) {
                throw new Exception("Not Enough Stocks For " . $variant["name"]);
            }
        }
    }

    // Load countries data
    $countries = require './utils/countries.php';
    $country = $countries[$filtered_data['delivery']['country']];
    $subtotal = array_reduce($selected_variants, function ($carry, $each) use ($variant_model) {
        return $carry + ($each[$variant_model->getColumnUnitPrice()] * $each["quantity"]);
    }, 0);

    // Apply discount if available
    if ($discount) {
        $subtotal -= round(($subtotal * 0.01 * $discount[$discount_model->getColumnAmount()]), 2);
    }

    // Calculate total price
    $total = $subtotal + $country["shipping"];

    // Create an order record
    $order_code = $order_model->generateOrderCode();
    $order_data = [
        $order_model->getColumnUserId() => $user[$user_model->getColumnId()],
        $order_model->getColumnTotalPrice() => $total,
        $order_model->getColumnUsedDiscountId() => $discount ? $discount[$discount_model->getColumnId()] : null,
        $order_model->getColumnFirstName() => $filtered_data["delivery"]["first_name"],
        $order_model->getColumnLastName() => $filtered_data["delivery"]["last_name"],
        $order_model->getColumnCompany() => $filtered_data["delivery"]["company"],
        $order_model->getColumnAddress() => $filtered_data["delivery"]["address"],
        $order_model->getColumnApartment() => $filtered_data["delivery"]["apartment"],
        $order_model->getColumnPostalCode() => $filtered_data["delivery"]["postal_code"],
        $order_model->getColumnCity() => $filtered_data["delivery"]["city"],
        $order_model->getColumnCountry() => $filtered_data['delivery']['country'],
        $order_model->getColumnShippingFee() => $country["shipping"],
        $order_model->getColumnPhone() => $filtered_data["delivery"]["phone"],
        $order_model->getColumnOrderCode() => $order_code
    ];

    if (!$order_model->validateFormData($order_data)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    $record_created = $order_model->create($order_data);
    if (!$record_created) {
        throw new Exception("Order creation failed.");
    }

    // Create order-variant records
    $order = $order_model->get(
        conditions: [
            $order_model->getColumnOrderCode() => $order_code
        ]
    );
    foreach ($selected_variants as $variant) {
        $order_variant_created = $order_variant_model->create([
            $order_variant_model->getColumnOrderId() => $order[$order_model->getColumnId()],
            $order_variant_model->getColumnVariantId() => $variant[$variant_model->getColumnId()],
            $order_variant_model->getColumnQuantity() => $variant["quantity"],
            $order_variant_model->getColumnPriceAtOrder() => $variant[$variant_model->getColumnUnitPrice()]
        ]);
        if (!$order_variant_created) {
            throw new Exception("Order variant creation failed.");
        }

        // Update Discount
        if ($discount) {
            $update_successful = $discount_model->update(
                [
                    $discount_model->getColumnUsedCount() => ++$discount[$discount_model->getColumnUsedCount()]
                ],
                [
                    $discount_model->getColumnId() => $discount[$discount_model->getColumnId()]
                ]
            );

            if (!$update_successful) {
                throw new Exception("Discount update failed.");
            }
        }

        // Reduce Quantity in Inventory
        $inventory = $inventory_model->get(
            conditions: [
                $inventory_model->getColumnVariantId() => $variant[$variant_model->getColumnId()]
            ]
        );

        $inventory_updated = $inventory_model->update(
            [
                $inventory_model->getColumnStockQuantity() => $inventory[$inventory_model->getColumnStockQuantity()] - $variant["quantity"]
            ],
            [
                $inventory_model->getColumnId() => $inventory[$inventory_model->getColumnId()]
            ]
        );

        if (!$inventory_updated) {
            throw new Exception("Inventory update failed.");
        }
    }



    if ($filtered_data["delivery"]["save_address"]) {
        $address_created = $address_model->create([
            $address_model->getColumnUserId() => $order_data[$order_model->getColumnUserId()],
            $address_model->getColumnFirstName() => $order_data[$order_model->getColumnFirstName()],
            $address_model->getColumnLastName() => $order_data[$order_model->getColumnLastName()],
            $address_model->getColumnCompany() => $order_data[$order_model->getColumnCompany()],
            $address_model->getColumnAddress() => $order_data[$order_model->getColumnAddress()],
            $address_model->getColumnApartment() => $order_data[$order_model->getColumnApartment()],
            $address_model->getColumnPostalCode() => $order_data[$order_model->getColumnPostalCode()],
            $address_model->getColumnCity() => $order_data[$order_model->getColumnCity()],
            $address_model->getColumnCountry() => $order_data[$order_model->getColumnCountry()],
            $address_model->getColumnPhone() => $order_data[$order_model->getColumnPhone()],
        ]);

        if (!$address_created) {
            throw new Exception("Address creation failed.");
        }
    }

    setcookie("CART", '', time() - (30 * 24 * 60 * 60), '/');

    //send order receipt
    $emailDetails = [
        "subject" => "Your Pulsar Gaming Gear Order Confirmation",
        "body" => "
            <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
            <h2 style='color: #000;'>Thank You for Your Order!</h2>
            <p style='margin-bottom: 16px;'>Hi <strong>" . htmlspecialchars($filtered_data['delivery']['first_name']) . " " . htmlspecialchars($filtered_data['delivery']['last_name']) . "</strong>,</p>
            <p style='margin-bottom: 16px;'>We have received your order <strong>#{$order_code}</strong> and it is now being processed.</p>
            <h3 style='color: #000; margin-bottom: 8px;'>Order Details:</h3>
            <ul style='list-style-type: none; padding: 0;'>
                <li style='margin-bottom: 8px;'><strong>Order Code:</strong> {$order_code}</li>
                <li style='margin-bottom: 8px;'><strong>Total Amount:</strong> $" . number_format($total, 2) . "</li>
                <li><strong>Shipping Address:</strong> " 
                    . htmlspecialchars($filtered_data['delivery']['address']) . ", "
                    . htmlspecialchars($filtered_data['delivery']['city']) . ", "
                    . htmlspecialchars($filtered_data['delivery']['postal_code']) . ", "
                    . htmlspecialchars($filtered_data['delivery']['country']) . 
                "</li>
            </ul>
            <p style='margin-top: 16px;'>We will notify you once your order has shipped.</p>
        </div>
        "
    ];
    

    ErrorHandler::handle(fn () => $mail_service->sendMail($filtered_data["user_email"], $emailDetails));

});

// If we reach here, everything was successful
if ($result) {
    $_SESSION["recent_order"] = true;
    $_SESSION["recent_order_time"] = time();
    header("Location: " . $root_directory . "thank-you");
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
exit();