<?php
include_once '../../shared/header.php';
include_once '../../config/db.php';
include_once '../../objects/news.php';
include_once '../../shared/utilities.php';
include_once '../../verify/middleware.php';
  
$database = new Database();
$db = $database->getConnection();

$utilities = new Utilities();
$news = new News($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->title)
){
    $news->title = $data->title;
    $news->subtitle = $data->subtitle;
    $news->thumdbImg = $data->thumdbImg;
    $news->_public = $data->_public;
    // $news->linkTo = $data->linkTo;
  
    // create the news
    if($news->create())
    {
        $news->created = $utilities->getLastInsertDate($db, $news->table_name, $news->id);
        http_response_code(201);
        echo json_encode(array("id" => $news->id, "message" => "ok", "created" => $news->created));
    }
    else
    {
        http_response_code(503);
        echo json_encode(array("error" => $news->error));
    }
}
else
{
    http_response_code(400);
    echo json_encode(array("error" => "Bad request"));
}
?>