<?php
include_once '../../config/db.php';
include_once '../../objects/specialist.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Connection: close");

$database = new Database();
$db = $database->getConnection();
  
$specialist = new Specialist($db);

$data = json_decode(file_get_contents("php://input"));

$stmt = $specialist->readOwnerByID($data->id);
$num = $stmt->rowCount();

if ($num > 0)
{
    $owners=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $owner=array(
            "id" => $id,
            "name" => $name,
            "image" => $image,
            "phone" => $phone,
            "email" => $email,
            "place" => $place,
        );
        // print_r($owner) ;
        array_push($owners, $owner);
    }
    http_response_code(200);
    echo json_encode($owners);
}
else
{
    http_response_code(404);
    echo json_encode(
        array("message" => "No owner found.")
    );
}