<?php
// include_once "config.production.php";
require_once dirname(__FILE__) . "/../checks.php";

class Database
{
    // specify your own database credentials
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    public $conn;

    public function __construct()
    {
      if (isProduction()){
        $this->db_name = "";
        $this->username = "";
        $this->password = "";
      }
      
    }
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->conn->exec("set names utf8");
            $this->conn->exec("SET SESSION group_concat_max_len = 1000000");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>