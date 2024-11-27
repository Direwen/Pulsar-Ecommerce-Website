<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$result = $error_handler->handleDbOperation(function () use ($event_model) {

    $event = $event_model->get([
        $event_model->getColumnId() => $_POST["id"] ?? 0
    ]);

    if(!$event) throw new Exception("Not Found");

    if($event_model->delete([$event_model->getColumnId() => $_POST["id"]])) {

        $oldFilePath = "./assets/events/" . $event[$event_model->getColumnBannerImg()];
        if (file_exists($oldFilePath)) unlink($oldFilePath);

    } else {

        throw new Exception("Failed to delete"); 
    
    }

});

if ($result) setMessage("Delete the event", "success");
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();