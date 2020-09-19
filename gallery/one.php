<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/db.php';
include_once '../objects/gallery.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$gallery = new Gallery($db);
  
// set ID property of record to read
$gallery->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of gallery to be edited
$gallery->readOne();

if($gallery->title!=null){
    // create array
    $gallery_arr = array(
        // "id" =>  $gallery->id,
        "title" => $gallery->title,
        "subtitle" => $gallery->subtitle,
        // "thumdbImg" => $gallery->thumdbImg,
        // "linkTo" => $gallery->linkTo,
        "images" => $gallery->images,
        "created" => $gallery->created
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($gallery_arr);
}
else
{
    http_response_code(404);
    echo json_encode(array("message" => "Gallery does not exist."));
}
?>