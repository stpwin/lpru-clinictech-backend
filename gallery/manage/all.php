<?php
include_once '../../shared/header.php';
include_once '../../verify/middleware.php';
include_once '../../config/db.php';
include_once '../../objects/gallery.php';

$database = new Database();
$db = $database->getConnection();

$gallery = new Gallery($db);

$stmt = $gallery->readManage();
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
            "_public" => filter_var($_public, FILTER_VALIDATE_BOOLEAN),
            "imageCount" => intval($imageCount),
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
  