<?php
class Downloads
{
  private $conn;
  private $table_name = "downloads";

  public function __construct($db){
      $this->conn = $db;
  }

  function read(){
    $query = "SELECT d.id, d.title, GROUP_CONCAT(df.fileName) AS fileNames, GROUP_CONCAT(df.fileUrl) AS fileUrls, GROUP_CONCAT(df.created) AS createds
    FROM {$this->table_name} d
    JOIN {$this->table_name}_file df ON d.id=df.downloadsID
    GROUP BY s.id
    ORDER BY d.created DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }
}
  
?>