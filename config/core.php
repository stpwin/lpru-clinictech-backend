<?php
require_once "../checks.php";

// home page url
if (isProduction())
{
  $home_url="http://www.clinictech.scilpru.in.th/api/";
  ini_set('display_errors', 0);
} else {
  $home_url="http://clinictech.local/";
  // show error reporting
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
}

  
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
  
// set number of records per page
$records_per_page = 5;
  
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>