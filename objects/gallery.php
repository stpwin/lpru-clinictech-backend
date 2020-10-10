<?php
class Gallery
{
    // database connection and table name
    private $conn;
    public $table_name = "gallery";
  
    // object properties
    public $id;
    public $title;
    public $subtitle;
    public $thumdbImg;
    public $imageCount;
    public $images;
    public $created;
    public $_public;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read news
  function read(){
  
    // select all query
    $query = "SELECT id, title, subtitle, thumdbImg, imageCount, created
    FROM {$this->table_name}
    WHERE _public=1
    ORDER BY created DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
  }

  function readManage(){
    $query = "SELECT id, title, subtitle, thumdbImg, imageCount, created, _public
    FROM {$this->table_name}
    ORDER BY created DESC";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();
  
    return $stmt;
  }

  // create gallery
  function create(){
    $query = "INSERT INTO {$this->table_name}
    SET title=:title, subtitle=:subtitle, thumdbImg=:thumdbImg";
  
    $stmt = $this->conn->prepare($query);
    

    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->subtitle=htmlspecialchars(strip_tags($this->subtitle));
    $this->thumdbImg=htmlspecialchars(strip_tags($this->thumdbImg));
  
    // bind values
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":subtitle", $this->subtitle);
    $stmt->bindParam(":thumdbImg", $this->thumdbImg);

    // execute query
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
        }
      }
    }
    return false;
  }

  function setPublic(){
    $query = "UPDATE {$this->table_name}
    SET _public = :_public
    WHERE id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->_public=htmlspecialchars(strip_tags($this->_public));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind new values
    $stmt->bindParam(':_public', $this->_public);
    $stmt->bindParam(':id', $this->id);
  
    // execute the query
    try {
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      $this->error = "ผิดพลาด";
      if (ini_get('display_errors')){
        $this->error = $e->getMessage();
      }
    }
    return false;
  }

  // used when filling up the update news form
  function readOne(){
  
    // query to read single record
    $query = "SELECT g.title, g.subtitle, g.created
    /* , GROUP_CONCAT(gi.imageUrl) AS images */
    FROM {$this->table_name} g
    /* JOIN {$this->table_name}_images gi ON g.id=gi.galleryID */
    WHERE g.id = ? AND g._public=1
    /* ORDER BY g.id */
    LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    try {
      $stmt->execute();
      
    } catch (PDOException $e) {
      $this->error = "ผิดพลาด";
      if (ini_get('display_errors')){
        $this->error = $e->getMessage();
      }
      return false;
    }
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // set values to object properties
    $this->title = $row['title'];
    $this->subtitle = $row['subtitle'];
    // $this->imageCount = $row['imageCount'];
    // $this->images = explode(",", $row['images']);
    $this->created = $row['created'];
  }

  // update the news
  function update(){
  
    // update query
    $query = "UPDATE {$this->table_name}
    SET title = :title, subtitle = :subtitle
    WHERE id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->subtitle=htmlspecialchars(strip_tags($this->subtitle));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind new values
    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':subtitle', $this->subtitle);
    $stmt->bindParam(':id', $this->id);
  
    // execute the query
    try {
      $stmt->execute();
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

  function updateImage(){
    $query = "UPDATE {$this->table_name}
    SET thumdbImg=:thumdbImg
    WHERE id=:id";

    $stmt = $this->conn->prepare($query);
    $this->thumdbImg=htmlspecialchars(strip_tags($this->thumdbImg));

    $stmt->bindParam(":thumdbImg", $this->thumdbImg);
    $stmt->bindParam(":id", $this->id);

    try {
      $stmt->execute();
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
  
  // delete the news
  function delete(){
    $query = "DELETE FROM {$this->table_name}
    WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    try {
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      if ($e->getCode() == "23000"){
        $this->error = "ซ้ำกับในระบบ";
      } else {
        if (ini_get('display_errors')){
          $this->error = $e->getMessage();
        }
      }
    }
  
    return false;
  }

  // search news
  function search($keywords){
  
    // select all query
    $query = "SELECT * FROM {$this->table_name} WHERE title LIKE ? OR subtitle LIKE ? ORDER BY created DESC LIMIT 0, 10";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
  
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
  }

  // read news with pagination
  public function readPaging($from_record_num, $records_per_page){
  
    // select query
    $query = "SELECT * FROM {$this->table_name} ORDER BY created DESC LIMIT ?, ?";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
  
    // execute query
    $stmt->execute();
  
    // return values from database
    return $stmt;
  }

  // used for paging products
  public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM {$this->table_name}";
  
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    return $row['total_rows'];
  }

}
  
?>