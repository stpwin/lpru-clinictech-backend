<?php
include_once '../../config/db.php';
include_once '../../objects/downloads.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();
  
$downloads = new Downloads($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id)
){
    $downloads->id = $data->id;
    if($downloads->deleteDownload()){
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