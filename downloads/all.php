<?php
include_once '../config/db.php';
include_once '../objects/downloads.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();
  
$downloads = new Downloads($db);

$stmt = $downloads->read();
$num = $stmt->rowCount();

function map_callback($id, $fileName, $fileUrl, $created)
{
    if (empty($fileName)) return;
    return array(
        "id" => $id,
        "name" => $fileName,
        "url" => $fileUrl,
        "created" => $created
    );
}

if ($num > 0)
{
    $downloads_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $files = array_filter(array_map('map_callback', explode(",", $fileIDs), explode(",", $fileNames), explode(",", $fileUrls), explode(",", $createds)));
        $downloads_item=array(
            "id" => $id,
            "title" => $title,
            "files" => $files
        );
        array_push($downloads_arr, $downloads_item);
    }
    http_response_code(200);
    echo json_encode($downloads_arr);
}
else
{
    http_response_code(404);
    echo json_encode(
        array("message" => "No downloads found.")
    );
}
  