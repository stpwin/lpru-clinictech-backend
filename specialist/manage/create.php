<?php
include_once '../../config/db.php';
include_once '../../objects/specialist.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();
  
$specialist = new Specialist($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->title)
){
    $specialist->title = $data->title;
    $specialist->thumbnail = $data->thumbnail;

    if($specialist->create()){
        http_response_code(201);
        echo json_encode(array("specialist_id" => $specialist->id, "created" => $specialist->created));
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