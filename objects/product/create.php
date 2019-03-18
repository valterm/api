<?php
//req headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include the db and the object files
include_once '../config/database.php';
include_once '../objects/product.php';

//instantiate the db
$database = new Database();
$db = $database->getConnection();

//init object
$product = new Product($db);

//get posted data
$data = json_decode(file_get_contents("php://input"));

//check so it's not empty
if (
  empty($data->name) ||
  empty($data->price) ||
  empty($data->description) ||
  empty($data->category_id)
){
  //set reponse code
  http_response_code(400);

  echo json_encode(array("message"=>"Unable to create product. A required value was empty."));
}
else{
  $product->name = $data->name;
  $product->price = $data->price;
  $product->description = $data->description;
  $product->category_id = $data->category_id;
  $product->created = date('Y-m-d H:i:s');

  if ($product->create()){
    //success
    http_response_code(201);

    echo json_encode(array("message"=>"Product successfully created."));
  }
  else{
    //service unavailable
    http_response_code(503);

    echo json_encode(array("message"=>"Unable to create product."));
  }
}

?>
