<?php
// include database and object files
include_once '../config/db.php';
include_once '../objects/gallery.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$gallery = new Gallery($db);
  
// read gallery will be here
// query gallery
$stmt = $gallery->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // gallery array
    $gallery_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $gallery_item=array(
            "id" => $id,
            "title" => $title,
            "subtitle" => html_entity_decode($subtitle),
            "thumdbImg" => $thumdbImg,
            // "linkTo" => $linkTo,
            "created" => $created
        );
  
        array_push($gallery_arr, $gallery_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show gallery data in json format
    echo json_encode($gallery_arr);
}
else
{
    http_response_code(404);
    echo json_encode(
        array("message" => "No gallery found.")
    );
}
  