<?php
include_once '../../shared/header.php';
include_once '../../verify/middleware.php';
include_once '../../config/db.php';
include_once '../../objects/gallery.php';

$database = new Database();
$db = $database->getConnection();
  
$gallery = new Gallery($db);
  
$data = json_decode(file_get_contents("php://input"));
  
$gallery->id = $data->id;
$gallery->_public = $data->_public;


if($gallery->setPublic()){
    sleep(1);
    http_response_code(200);
    echo json_encode(array("message" => "ok", "_public"=> filter_var($data->_public, FILTER_VALIDATE_BOOLEAN)));
}
else
{
    http_response_code(503);
    echo json_encode(array("error" => "ผิดพลาด"));
}