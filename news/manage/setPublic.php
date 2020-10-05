<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/db.php';
include_once '../../objects/news.php';

$database = new Database();
$db = $database->getConnection();
  
$news = new News($db);
  
$data = json_decode(file_get_contents("php://input"));
  
$news->id = $data->id;
$news->_public = $data->_public;


if($news->setPublic()){
    sleep(1);
    http_response_code(200);
    echo json_encode(array("message" => "ok", "_public"=> filter_var($data->_public, FILTER_VALIDATE_BOOLEAN)));
}
else
{
    http_response_code(503);
    echo json_encode(array("error" => "ผิดพลาด"));
}