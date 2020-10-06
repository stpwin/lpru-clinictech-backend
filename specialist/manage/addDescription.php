<?php
include_once '../../shared/header.php';
include_once '../../config/db.php';
include_once '../../objects/specialist.php';
include_once '../../verify/middleware.php';

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->specialist_id) &&
    !empty($data->description)
){
    $database = new Database();
    $db = $database->getConnection();
    $specialist = new Specialist($db);
    $specialist->id = $data->specialist_id;
    $specialist->description = $data->description;

    http_response_code(201);
    echo json_encode(array("message" => "ok"));

    return;
    // sleep(1);
    if($specialist->addDescription()){
        http_response_code(201);
        echo json_encode(array("specialist_id" => $specialist->id, "description_id" => $specialist->description_id));
    } else {
      http_response_code(406);
      echo json_encode(array("error" => $specialist->error));
    }

} else {
  http_response_code(400);
    echo json_encode(
        array("message" => "Bad request.")
    );
}