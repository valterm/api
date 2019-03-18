<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//include the db and the object files
include_once '../config/database.php';
include_once '../objects/product.php';

//instantiate the db
$database = new Database();
$db = $database->getConnection();

//init object
$product = new Product($db);


//set ID prop of the read record
//$product->id = isset($_GET['id']) ? $_GET['id'] : die();
$data = json_decode(file_get_contents("php://input"));
empty($data->id) ? die() : $product->id=$data->id;

//read the details of the product
$product->readOne();

if($product->name!=null){
  //create the array
  $product_array = array(
    "id" => $product->id,
    "name" => $product->name,
    "description" => $product->description,
    "price" => $product->price,
    "category_id" => $product->category_id,
    "category_name" => $product->category_name,
    "created" =>$product->created
  );

  //set response 200
  http_response_code(200);

  //make it json
  echo json_encode($product_array);
}

else{
  //set response 404
  http_response_code(404);
  echo json_encode(array("message"=> "You suck."));
}

?>
