<?php
Class Product{
  //db conn + table name
  private $conn;
  private $table_name="products";

  //obj props
  public $id;
  public $name;
  public $description;
  public $price;
  public $category_id;
  public $created;
  public $modified;

  //constructor with $db for db connection
  public function __construct($db){
    $this->conn=$db;
  }

  // read products
function read(){
    // select all query
    $query = "SELECT p.id, p.name, p.description, p.price, p.category_id, c.name, p.created FROM products p
    LEFT JOIN categories c ON c.id = p.product_id;";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  function create(){
    //insert
    $query = "INSERT INTO ". $this->table_name . "
    SET name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

    //prepare the query
    $stmt = $this->conn->prepare($query);

    // sanitize that shit
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->created=htmlspecialchars(strip_tags($this->created));

    // bind the values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":created", $this->created);

    //execute the query
    if($stmt->execute()){
      return true;
    }

    return false;

  }

  function readOne(){
      // select all query
      $query = "SELECT p.id, p.name, p.description, p.price, p.category_id, c.name as category_name, p.created FROM products p
        LEFT JOIN categories c ON p.category_id=c.id
        WHERE p.id = ?
        LIMIT 0,1;";

      // prepare query statement
      $stmt = $this->conn->prepare($query);

      //bind the id
      $stmt->bindParam(1, $this->id);

      // execute query
      $stmt->execute();

      //retrieve row
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      //set values to the properties of the object
      $this->name=$row['name'];
      $this->price=$row['price'];
      $this->description=$row['description'];
      $this->category_id = $row['category_id'];
      $this->category_name = $row['category_name'];
      $this->created=$row['created'];
    }

  function update(){
    $query = "UPDATE " . $this->table_name. "
    SET name=:name, price=:price, description=:description, category_id=:category_id
    WHERE id=:id";

    //prep
    $stmt = $this->conn->prepare($query);

    //sanitize that input, yo
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

    //bind the new values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':id', $this->id);

    //exec
    if ($stmt->execute()){
      return true;
    }
    else{
      return false;
    }
  }

  function delete(){
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    //prep
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    //exec
    if ($stmt->execute()){
      return true;
    }
    else{
      return false;
    }

  }
}
?>
