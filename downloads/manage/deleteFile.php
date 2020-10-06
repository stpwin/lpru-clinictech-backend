<?php
include_once '../../shared/header.php';
include_once '../../verify/middleware.php';
include_once '../../config/db.php';
include_once '../../objects/downloads.php';

$database = new Database();
$db = $database->getConnection();
  
$downloads = new Downloads($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id)
){
    if($downloads->deleteFile($data->id)){
        http_response_code(200);
        echo json_encode(array("id" => $data->id));
    } else {
      http_response_code(406);
      echo json_encode(array("error" => $downloads->error));
    }

} else {
  http_response_code(400);
    echo json_encode(
        array("message" => "Bad request.")
    );
}