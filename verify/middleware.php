<?php
include_once '../../config/core.php';
include_once '../../verify/verify.php';

$auth = new FirebaseToken();

function get_header($headerName)
{
    $headers = getallheaders();
    return (isset($headerName) && isset($headers[$headerName]))  ? $headers[$headerName] : null;
}

$id_token = get_header("X-Token");

if (!empty($id_token)){
    if (!$auth->verify($id_token)) {
        http_response_code(406);
        echo json_encode(array("error" => "Token is not valid: ".$auth->lastError));
        die();
    }

} else {
    http_response_code(400);
    echo json_encode(array("error" => "bad request"));
    die();
}