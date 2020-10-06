<?php
include_once '../../shared/header.php';
include_once '../../verify/middleware.php';
include_once '../../config/db.php';
include_once '../../objects/downloads.php';

$database = new Database();
$db = $database->getConnection();
  
$downloads = new Downloads($db);
// $file = new File($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->files)
){
    $downloads->files = $data->files;
    if($downloads->createFiles()){
        http_response_code(201);
        echo json_encode(array("message" => "ok"));
    } else {
        http_response_code(406);
      echo json_encode(array("error" => $downloads->error));
    }

} else {
  http_response_code(400);
    echo json_encode(
        array("message" => "Bad request.")
    );
}