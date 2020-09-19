<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/core.php';
include_once '../config/db.php';
include_once '../objects/news.php';
  
// instantiate database and news object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$news = new News($db);
  
// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
  
// query news
$stmt = $news->search($keywords);
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // news array
    $news_arr=array();
    $news_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $news_item=array(
            "id" => $id,
            "title" => $title,
            "subtitle" => html_entity_decode($subtitle),
            "thumdbImg" => $thumdbImg,
            // "linkTo" => $linkTo,
            "created" => $created
        );
  
        array_push($news_arr["records"], $news_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show news data
    echo json_encode($news_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no news found
    echo json_encode(
        array("message" => "No news found.")
    );
}
?>