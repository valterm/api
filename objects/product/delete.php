<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//include the db and the object files
include_once '../config/database.php';
include_once '../objects/product.php';

//instantiate db
$database = new Database();
$db = $database->getConnection();

//init object
$product = new Product($db);


// get product id
$data = json_decode(file_get_contents("php://input"));

// set product id to be deleted
$product->id = $data->id;

//delete the stuff
if($product->delete()){
  //set response ok
  http_response_code(200);

  echo json_encode(array("message" => "Product deleted."));

}
else {
  //bad reposnse
  http_response_code(503);

  echo json_encode(array("message" => "Unable to delete product."));

}

?>
