<?php

header('Content-Type: application/json');



echo json_encode(['records' => $order_model->getRevenueTrend()]);