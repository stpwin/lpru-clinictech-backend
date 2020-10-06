<?php
include_once '../../shared/header.php';
include_once '../../verify/middleware.php';
include_once '../../config/db.php';
include_once '../../objects/gallery.php';
include_once '../../shared/utilities.php';
  
$database = new Database();
$db = $database->getConnection();

$utilities = new Utilities();
$gallery = new Gallery($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->title)
){
    $gallery->title = $data->title;
    $gallery->subtitle = $data->subtitle;
    $gallery->thumdbImg = $data->thumdbImg;
  
    // create the news
    if($gallery->create())
    {
        $gallery->created = $utilities->getLastInsertDate($db, $gallery->table_name, $gallery->id);
        http_response_code(201);
        echo json_encode(array("id" => $gallery->id, "message" => "ok", "created" => $gallery->created));
    }
    else
    {
        http_response_code(503);
        echo json_encode(array("error" => $gallery->error));
    }
}
else
{
    http_response_code(400);
    echo json_encode(array("error" => "Bad request"));
}
?>