<?php

// $events = ErrorHandler::handle(fn () => $event_model->getEverything(
//     select: [
//         ["column" => $event_model->getColumnName()],
//         ["column" => $event_model->getColumnBannerImg()]
//     ],
//     aggregates: [
//         [
//             "column" => $event_product_model->getColumnProductId(), 
//             "function" => "GROUP_CONCAT", 
//             "alias" => "event_products", 
//             "table" => $event_product_model->getTableName()
//         ],

//     ],
//     groupBy: $event_product_model->getTableName() . "." . $event_product_model->getColumnEventId(),
//     joins: [
//         [
//             "type" => "INNER JOIN",
//             "table" => $event_product_model->getTableName(),
//             "on" => "event_products.event_id=events.id"
//         ]
//     ],
//     conditions: [
//         [
//             'attribute' => $event_model->getColumnEndAt(),
//             'operator' => ">",
//             'value' => $event_model->getTimestampString(time())
//         ]
//     ]
// ));

require("views/home/home.view.php");