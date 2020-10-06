<?php


header("Access-Control-Allow-Origin: *");
// header('Access-Control-Allow-Credentials: true');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT, PATCH");
header("Access-Control-Allow-Headers: X-Token, Accept, Content-Type, Authorization, X-Requested-With");

header("Content-Type: application/json; charset=UTF-8");
header("Connection: close");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {    
    http_response_code(200);
    die();
 }    

?>