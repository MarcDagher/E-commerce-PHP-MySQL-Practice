<?php 
include "../connection.php";

$name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null;
$price = isset($_POST['price']) && !empty($_POST['price']) ? $_POST['price'] : null;
$stock = isset($_POST['stock']) && !empty($_POST['stock']) ? $_POST['stock'] : null;

$id = 7; // to be changed maybe we get it from token


$check = $mysqli -> prepare("select product_name from products where product_name = ? and user_id = ? ");
$check -> bind_param('si', $name, $id);
$check -> execute();
$result = $check -> get_result();
$rows = $result -> num_rows;
$check -> close();


if ($rows == 0){
  $query = $mysqli -> prepare("insert into products(product_name, price, stock, user_id) values(?,?,?,?)");
  $query-> bind_param("siii", $name, $price, $stock, $id);
  $query -> execute();
  $query -> close();
  
  $response = [];
  $response['status'] = $name . " added successully";
  echo json_encode($response);
} elseif ($rows > 0){
  echo "already found";
}
