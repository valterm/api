<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include the db and the object files
include_once '../config/database.php';
include_once '../objects/product.php';

//instantiate db
$database = new Database();
$db = $database->getConnection();

//init object
$product = new Product($db);

//query the products
$stmt = $product->read();
$num = $stmt->rowCount();


//0< number of records?
if ($num>0){
  //products array
  $products_array = array();
  $products_array["records"]=array();

  //retrieve all products
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //extract rows
    extract($row);

    $product_item = array(
      "id" => $id,
      "name" => $name,
      "description" => $description,
      "price" => $price,
      "category_id" => $category_id,
    );
    array_push($products_array["records"], $product_item);
}
  //response OK
  http_response_code(200);

  //encode to json
  echo json_encode($products_array);
}

else{

  //404 response
  http_response_code(404);

  //json response
  echo json_encode(
    array("message"=>"No products found.")
  );
}

?>
