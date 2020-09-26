<?php
class Specialist
{
    private $conn;
    private $table_name = "specialist";
  
    public $id;
    public $title;
    public $description;
    public $thumbnail;
    public $created;

    public $description_id;

    // public $images;
    // public $names;
    // public $phones;
    // public $emails;
    // public $places;
  
    public function __construct($db){
        $this->conn = $db;
    }

  function read(){
    $query = "SELECT s.id, s.title, s.thumbnail, s.created,  GROUP_CONCAT(sd.id) AS descriptionIDs, GROUP_CONCAT(sd.description) AS descriptions, so.ownerIDs, so.images, so.names, so.phones, so.emails, so.places
    FROM {$this->table_name} s
    LEFT JOIN (SELECT specialistID, GROUP_CONCAT(o.id) AS ownerIDs, GROUP_CONCAT(o.image) AS images, GROUP_CONCAT(o.name) AS names, GROUP_CONCAT(o.phone) AS phones, GROUP_CONCAT(o.email) AS emails, GROUP_CONCAT(o.place) AS places
                FROM {$this->table_name}_owner so
                LEFT JOIN `owner` o ON o.id=ownerID
                GROUP BY specialistID) so ON so.specialistID=s.id
    
    LEFT JOIN {$this->table_name}_description sd ON sd.specialistID=s.id
    GROUP BY s.id
    ORDER BY s.created DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  function create(){
    $query = "INSERT INTO {$this->table_name}
    SET title=:title, thumbnail=:thumbnail";

    $stmt = $this->conn->prepare($query);
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->thumbnail=htmlspecialchars(strip_tags($this->thumbnail));
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":thumbnail", $this->thumbnail);

    try {
      $stmt->execute();
      $this->id = $this->conn->lastInsertId();
      $this->created = date("Y-m-d H:i:s");
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

  function changeTitle(){
    $query = "UPDATE {$this->table_name}
    SET title=:title
    WHERE id=:id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":id", $this->id);

    try {
      $stmt->execute();
      // $this->id = $this->conn->lastInsertId();
      // $this->created = date("Y-m-d H:i:s");
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

  function changeImage(){
    $query = "UPDATE {$this->table_name}
    SET thumbnail=:thumbnail
    WHERE id=:id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":thumbnail", $this->thumbnail);
    $stmt->bindParam(":id", $this->id);

    try {
      $stmt->execute();
      // $this->id = $this->conn->lastInsertId();
      // $this->created = date("Y-m-d H:i:s");
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

  function removeSpecialist($id){
    $query = "DELETE FROM {$this->table_name}
    WHERE id=:id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $id);

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

  function addDescription(){
    $query = "INSERT INTO {$this->table_name}_description
    SET description=:description, specialistID=:specialistID";

    $stmt = $this->conn->prepare($query);
    $this->description=htmlspecialchars(strip_tags($this->description));

    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":specialistID", $this->id);

    try {
      $stmt->execute();
      $this->description_id = $this->conn->lastInsertId();
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

  function removeDescription($id){
    $query = "DELETE FROM {$this->table_name}_description
    WHERE id=:id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $id);

    try {
      $stmt->execute();
      // $this->id = $this->conn->lastInsertId();
      return true;
    } catch (PDOException $e) {
      $this->error = "ผิดพลาด";
      if (ini_get('display_errors')){
        $this->error = $e->getMessage();
      }
    }
    return false;
  }

}
  
?>