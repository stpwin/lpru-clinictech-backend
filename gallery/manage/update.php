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
$gallery->title = $data->title;
$gallery->subtitle = $data->subtitle;
  
if($gallery->update())
{
    sleep(1);
    http_response_code(200);
    echo json_encode(array("message" => "ok"));
}
else
{
    http_response_code(503);
    echo json_encode(array("error" => $gallery->error));
}