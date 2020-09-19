<?php
class Specialist
{
    private $conn;
    private $table_name = "specialist";
  
    public $id;
    public $title;
    public $describe;
    public $thumbnail;
    public $created;

    public $images;
    public $names;
    public $phones;
    public $emails;
    public $places;
  
    public function __construct($db){
        $this->conn = $db;
    }

  function read(){
    $query = "SELECT s.id, s.title, s.describe, s.thumbnail, s.created, GROUP_CONCAT(o.image) AS images, GROUP_CONCAT(o.name) AS names, GROUP_CONCAT(o.phone) AS phones, GROUP_CONCAT(o.email) AS emails, GROUP_CONCAT(o.place) AS places
    FROM {$this->table_name} s
    LEFT JOIN {$this->table_name}_owner so ON so.specialistID=s.id
    LEFT JOIN `owner` o ON o.id=so.ownerID
    GROUP BY s.id
    ORDER BY s.created DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

}
  
?>