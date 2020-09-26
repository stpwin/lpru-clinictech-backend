<?php
include_once '../config/db.php';
include_once '../objects/specialist.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//Enable below when production
// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");

$database = new Database();
$db = $database->getConnection();
  
$specialist = new Specialist($db);

$stmt = $specialist->read();
$num = $stmt->rowCount();

function map_callback($id, $image, $name, $phone, $email, $place)
{
    if (empty($name)) return;
    return array(
        "id" => $id,
        "image" => $image,
        "name" => $name,
        "phone" => $phone,
        "email" => $email,
        "place" => $place
    );
}

if ($num > 0)
{
    $specialist_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $owners = array_filter(array_map('map_callback', explode(",", $ownerIDs),explode(",", $images), explode(",", $names), explode(",", $phones), explode(",", $emails), explode(",", $places)));
        $specialist_item=array(
            "id" => $id,
            "title" => $title,
            "descriptions" => array_filter(explode(",", $descriptions)),
            "descriptionIDs" => array_filter(explode(",", $descriptionIDs)),
            "thumbnail" => $thumbnail,
            "created" => $created,
            "owner" =>  $owners,
        );
        array_push($specialist_arr, $specialist_item);
    }
    http_response_code(200);
    echo json_encode($specialist_arr);
}
else
{
    http_response_code(404);
    echo json_encode(
        array("message" => "No specialist found.")
    );
}
  