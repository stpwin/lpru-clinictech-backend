<?php
// include database and object files
include_once '../../config/db.php';
include_once '../../objects/news.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Connection: close");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$database = new Database();
$db = $database->getConnection();

$news = new News($db);

$stmt = $news->readManage();
$num = $stmt->rowCount();

if($num>0){
    $news_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $news_item=array(
            "id" => intval($id),
            "title" => $title,
            "subtitle" => html_entity_decode($subtitle),
            "thumdbImg" => $thumdbImg,
            "_public" => filter_var($_public, FILTER_VALIDATE_BOOLEAN),//intval($_public),
            "created" => $created
        );
  
        array_push($news_arr, $news_item);
    }

    http_response_code(200);
    echo json_encode($news_arr);
}
else{
    http_response_code(200);
    echo json_encode(
        array()
    );
}
  