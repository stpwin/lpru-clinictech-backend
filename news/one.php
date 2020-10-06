<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/db.php';
include_once '../objects/news.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$news = new News($db);
  
// set ID property of record to read
$news->id = isset($_GET['id']) ? $_GET['id'] : die();
  
// read the details of news to be edited
$news->readOne();

if($news->title!=null){
    // create array
    $news_arr = array(
        "title" => $news->title,
        "subtitle" => $news->subtitle,
        "content" => $news->content,
        "created" => $news->created
    );
  
    http_response_code(200);
    echo json_encode($news_arr);
}
else
{
    http_response_code(404);
    echo json_encode(array("error" => "News does not exist."));
}
?>