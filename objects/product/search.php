<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';

// instantiate the db
$database = new Database();
$db = $database->getConnection();

// init object object
$product = new Product($db);

// get the keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query products
$stmt = $product->search($keywords);
$num = $stmt->rowCount();

// check if there are results
if($num>0){

    // create an array from them
    $products_arr=array();
    $products_arr["records"]=array();

    // fetch the rows
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        // extract row
        extract($row);

        $product_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id
        );

        array_push($products_arr["records"], $product_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data
    echo json_encode($products_arr);
}

else{
    // set response code - 200 OK (the request worked, just no products)
    http_response_code(200);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>
