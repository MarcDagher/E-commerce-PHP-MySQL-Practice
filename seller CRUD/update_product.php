<?php 
include "../connection.php";

$name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null;
$seller_id = isset($_POST['seller_id']) && !empty($_POST['seller_id']) ? $_POST['seller_id'] : null;

// NEW VALUES
$product_name = isset($_POST['product_name']) && !empty($_POST['product_name']) ? $_POST['product_name'] : null;
$price = isset($_POST['price']) && !empty($_POST['price']) ? $_POST['price'] : null;
$stock = isset($_POST['stock']) && !empty($_POST['stock']) ? $_POST['stock'] : null;
$user_id = isset($_POST['user_id']) && !empty($_POST['user_id']) ? $_POST['user_id'] : null;


$query = $mysqli -> prepare("select product_name, user_id from products where product_name =? and user_id = ?");
$query -> bind_param('si', $name, $seller_id);
$query -> execute();
$result = $query -> get_result();
$rows = $result -> num_rows;
$query -> close();

if ($rows == 0){
  echo "Product not found";

} else {
  if ($seller_id == 7){
    $query = $mysqli -> prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, user_id = ? where product_name =? and user_id = ?");
    $query -> bind_param('siiisi', $product_name, $price, $stock, $user_id, $name, $seller_id);
    $query -> execute();
    echo "Item updated";
  }
  else {
    echo "Unauthorized.";
  }

}