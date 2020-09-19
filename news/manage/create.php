<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/db.php';
  
// instantiate product object
include_once '../objects/news.php';
  
$database = new Database();
$db = $database->getConnection();
  
$news = new News($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->title) &&
    // !empty($data->subtitle) &&
    !empty($data->linkTo)
){
  
    // set news property values
    $news->title = $data->title;
    $news->subtitle = $data->subtitle;
    $news->thumdbImg = $data->thumdbImg;
    $news->linkTo = $data->linkTo;
    $news->created = date('Y-m-d H:i:s');
  
    // create the news
    if($news->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "News was created."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create news. Data is incomplete."));
}
?>