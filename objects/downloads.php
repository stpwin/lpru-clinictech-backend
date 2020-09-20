<?php
class Downloads
{
  private $conn;
  private $table_name = "downloads";

  public $id;
  public $title;
  public $created;
  public $files;
  public $error = "ผิดพลาด";

  public function __construct($db){
      $this->conn = $db;
  }

  function read(){
    $query = "SELECT d.id, d.title, GROUP_CONCAT(df.id) AS fileIDs, GROUP_CONCAT(df.fileName) AS fileNames, GROUP_CONCAT(df.fileUrl) AS fileUrls, GROUP_CONCAT(df.created) AS createds
    FROM {$this->table_name} d
    LEFT JOIN {$this->table_name}_file df ON d.id=df.downloadsID 
    GROUP BY d.id
    ORDER BY d.created DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  function create(){
    $query = "INSERT INTO {$this->table_name}
    SET title=:title";
    $stmt = $this->conn->prepare($query);
    $this->title=htmlspecialchars(strip_tags($this->title));
    $stmt->bindParam(":title", $this->title);
    try {
      $stmt->execute();
      $this->id = $this->conn->lastInsertId();
      return true;
    } catch (PDOException $e) {
      if ($e->getCode() == "23000"){
        $this->error = "ซ้ำกับในระบบ";
      } else {
        if (ini_get('display_errors')){
          $this->error = $e->getMessage();
          // die($e->getMessage());
        }
      }
    }
    return false;
  }

  function placeholders($text, $count=0, $separator=","){
    $result = array();
    if($count > 0){
        for($x=0; $x<$count; $x++){
            $result[] = $text;
        }
    }
    return implode($separator, $result);
  }

  function createFiles(){
    $insert_values = array();
    foreach($this->files as $d){
      $question_marks[] = '(' . $this->placeholders('?', sizeof($d)) . ')';
      $insert_values = array_merge($insert_values, array_values($d));
    }

    $query = "INSERT INTO {$this->table_name}_file (downloadsID, fileName, fileUrl)
    VALUES " . implode(',', $question_marks);

    $this->conn->beginTransaction();
    $stmt = $this->conn->prepare($query);

    try {
      $stmt->execute($insert_values);
    } catch (PDOException $e) {
      if (ini_get('display_errors')){
        $this->error = $e->getMessage();
        // die($e->getMessage());
      }
      return false;
    }

    $this->conn->commit();
    return true;
  }

  function deleteDownload(){
    $query = "DELETE FROM {$this->table_name}
    WHERE id=:id";
    $stmt = $this->conn->prepare($query);
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(":id", $this->id);
    try {
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      if (ini_get('display_errors')){
        $this->error = $e->getMessage();
        // die($e->getMessage());
      }
    }
    return false;
  }

  function deleteFile($fileID){
    $query = "DELETE FROM {$this->table_name}_file
    WHERE id=:id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", htmlspecialchars(strip_tags($fileID)));
    try {
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      if (ini_get('display_errors')){
        $this->error = $e->getMessage();
        // die($e->getMessage());
      }
    }
    return false;
  }
}
  
?>