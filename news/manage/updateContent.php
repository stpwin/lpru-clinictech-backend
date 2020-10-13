<?php
include_once '../../shared/header.php';
include_once '../../verify/middleware.php';
include_once '../../config/db.php';
include_once '../../objects/news.php';

$database = new Database();
$db = $database->getConnection();

$news = new News($db);

$data = json_decode(file_get_contents("php://input"));

$news->id = $data->id;
$news->content = $data->content;
  
if($news->updateContent())
{
    sleep(1);
    http_response_code(200);
    echo json_encode(array("message" => "News was updated."));
}
else
{
    http_response_code(503);
    echo json_encode(array("error" => $news->error));
}