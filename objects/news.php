<?php
class News
{

    // database connection and table name
    private $conn;
    private $table_name = "news";
  
    // object properties
    public $id;
    public $title;
    public $subtitle;
    public $thumdbImg;
    // public $linkTo;
    public $content;
    public $created;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read news
  function read(){
  
    // select all query
    $query = "SELECT id, title, subtitle, thumdbImg, created FROM {$this->table_name} ORDER BY created DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
  }

  // create news
  function create(){
  
    // query to insert record
    $query = "INSERT INTO {$this->table_name} SET title=:title, subtitle=:subtitle, thumdbImg=:thumdbImg, content=:content";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->subtitle=htmlspecialchars(strip_tags($this->subtitle));
    $this->thumdbImg=htmlspecialchars(strip_tags($this->thumdbImg));
    // $this->linkTo=htmlspecialchars(strip_tags($this->linkTo));
    $this->content=htmlspecialchars(strip_tags($this->content));
    // $this->created=htmlspecialchars(strip_tags($this->created));
  
    // bind values
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":subtitle", $this->subtitle);
    $stmt->bindParam(":thumdbImg", $this->thumdbImg);
    // $stmt->bindParam(":linkTo", $this->linkTo);
    $stmt->bindParam(":content", $this->content);
    // $stmt->bindParam(":created", $this->created);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
  }

  // used when filling up the update news form
  function readOne(){
  
    // query to read single record
    $query = "SELECT title, content, created FROM {$this->table_name} WHERE id = ? LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // set values to object properties
    $this->title = $row['title'];
    // $this->subtitle = $row['subtitle'];
    // $this->thumdbImg = $row['thumdbImg'];
    // $this->linkTo = $row['linkTo'];
    $this->content = $row['content'];
    $this->created = $row['created'];
  }

  // update the news
  function update(){
  
    // update query
    $query = "UPDATE {$this->table_name} SET title = :title, subtitle = :subtitle, thumdbImg = :thumdbImg, content = :content WHERE id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->subtitle=htmlspecialchars(strip_tags($this->subtitle));
    $this->thumdbImg=htmlspecialchars(strip_tags($this->thumdbImg));
    // $this->linkTo=htmlspecialchars(strip_tags($this->linkTo));
    $this->content=htmlspecialchars(strip_tags($this->content));
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind new values
    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':subtitle', $this->subtitle);
    $stmt->bindParam(':thumdbImg', $this->thumdbImg);
    // $stmt->bindParam(':linkTo', $this->linkTo);
    $stmt->bindParam(':content', $this->content);
    $stmt->bindParam(':id', $this->id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
  }

  // delete the news
  function delete(){
  
    // delete query
    $query = "DELETE FROM {$this->table_name} WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
  }

  // search news
  function search($keywords){
  
    // select all query
    $query = "SELECT id, title, subtitle, thumdbImg, created FROM {$this->table_name} WHERE title LIKE ? OR subtitle LIKE ? ORDER BY created DESC LIMIT 0, 10";
  
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
    $query = "SELECT id, title, subtitle, thumdbImg, created FROM {$this->table_name} ORDER BY created DESC LIMIT ?, ?";
  
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