<?php
class DownloadFile
{
  private $conn;
  private $table_name = "downloads_file";

  private $fileID;
  private $downloadsID;
  private $fileName;
  private $fileUrl;
  private $fileCreated;


  public function __construct($db){
      $this->conn = $db;
  }

  // function read(){
  //   $query = "SELECT d.id, d.title, GROUP_CONCAT(df.fileName) AS fileNames, GROUP_CONCAT(df.fileUrl) AS fileUrls, GROUP_CONCAT(df.created) AS createds
  //   FROM {$this->table_name} d
  //   JOIN {$this->table_name}_file df ON d.id=df.downloadsID
  //   GROUP BY s.id
  //   ORDER BY d.created DESC";
  //   $stmt = $this->conn->prepare($query);
  //   $stmt->execute();
  //   return $stmt;
  // }

  function create(){
    $query = "INSERT INTO {$this->table_name}
    SET downloadsID=:downloadsID, fileName=:fileName, fileUrl=:fileUrl";
  
    $stmt = $this->conn->prepare($query);
  
    $this->downloadsID=htmlspecialchars(strip_tags($this->downloadsID));
    $this->fileName=htmlspecialchars(strip_tags($this->fileName));
    $this->fileUrl=htmlspecialchars(strip_tags($this->fileUrl));
  
    $stmt->bindParam(":downloadsID", $this->downloadsID);
    $stmt->bindParam(":fileName", $this->fileName);
    $stmt->bindParam(":fileUrl", $this->fileUrl);
  
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
  }
}
  
?>