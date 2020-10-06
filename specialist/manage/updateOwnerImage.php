<?php
include_once '../../shared/header.php';
include_once '../../config/db.php';
include_once '../../objects/specialist.php';
include_once '../../verify/middleware.php';

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->owner_id) &&
    !empty($data->image)
){
    $database = new Database();
    $db = $database->getConnection();
    $specialist = new Specialist($db);
    // sleep(1);
    if($specialist->updateOwnerImage($data->owner_id, $data->image)){
        http_response_code(200);
        echo json_encode(array("owner_id" => $data->owner->id, "message" => "ok"));
    
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