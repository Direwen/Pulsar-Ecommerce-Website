<?php

// Handle changes to the dashboard via query parameter
$status_search_for = 'all';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'all':
            $status_search_for = 'all';
            break;
        case 'pending':
            $status_search_for = 'pending';
            break;
        case 'delivered':
            $status_search_for = 'delivered';
            break;
        case 'not-yet-shipped':
            $status_search_for = 'not-yet-shipped';
            break;
        case 'cancelled':
            $status_search_for = 'cancelled';
            break;
        default:
            $status_search_for = 'all';
            break;
    }
}

$conditions = [];

if ($status_search_for == 'delivered') {
    $conditions[] = [
        'attribute' => $order_model->getColumnStatus(),
        'operator' => "LIKE",
        'value' => 'delivered'
    ];
} else if ($status_search_for == 'pending') {
    $conditions[] = [
        'attribute' => $order_model->getColumnStatus(),
        'operator' => "<>",
        'value' => 'shipping'
    ];
    $conditions[] = [
        'attribute' => $order_model->getColumnStatus(),
        'operator' => "<>",
        'value' => 'cancelled'
    ];
    $conditions[] = [
        'attribute' => $order_model->getColumnStatus(),
        'operator' => "<>",
        'value' => 'delivered'
    ];
} else if ($status_search_for == 'not-yet-shipped') {
    $conditions[] = [
        'attribute' => $order_model->getColumnStatus(),
        'operator' => "LIKE",
        'value' => 'shipping'
    ];
} else if ($status_search_for == 'cancelled') {
    $conditions[] = [
        'attribute' => $order_model->getColumnStatus(),
        'operator' => "LIKE",
        'value' => 'cancelled'
    ];
}


$orders = ErrorHandler::handle(fn() => $order_model->getEverything(
    conditions: [
        ...$conditions, 
        [
            "attribute" => $order_model->getColumnUserId(),
            "operator" => "=",
            "value" => $_SESSION["user_id"]
        ]
    ],
    sortField: $order_model->getColumnCreatedAt(),
    sortDirection: 'DESC'
));;


require('./views/ecommerce/history.view.php');
?>