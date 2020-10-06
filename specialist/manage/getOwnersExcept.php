<?php
include_once '../../shared/header.php';
include_once '../../config/db.php';
include_once '../../objects/specialist.php';
include_once '../../verify/middleware.php';

$database = new Database();
$db = $database->getConnection();
$specialist = new Specialist($db);
$stmt = $specialist->readOwners($data->except);
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
            // "phone" => $phone,
            // "email" => $email,
            // "place" => $place,
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