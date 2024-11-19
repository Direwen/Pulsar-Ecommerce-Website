<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$support_model->validateFormData($data)) echo json_encode(['success' => false]);

$result = ErrorHandler::handle(function () use ($data) {
    global $support_model;
    $support_model->create([
        $support_model->getColumnUserEmail() => $data[$support_model->getColumnUserEmail()],
        $support_model->getColumnSubject() => $data[$support_model->getColumnSubject()],
        $support_model->getColumnMessage() => $data[$support_model->getColumnMessage()],
    ]);

    return true;
});

echo json_encode(['success' => $result]);
exit();

