<?php

if (!function_exists('isProduction')){
  function isProduction()
  {
    if (isset($_ENV['PATH'])){
      return $_ENV['PATH'] == '/usr/local/bin:/usr/bin:/bin';
    }
  }
}

if (!function_exists('http_response_code'))
{
    function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if($newcode !== NULL)
        {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }       
        return $code;
    }
}

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