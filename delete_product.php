<?php 
include "./connection.php";

$name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null;
$user_id = isset($_POST['user_id']) && !empty($_POST['user_id']) ? $_POST['user_id'] : null;
// user id is the id we get from the token

$query = $mysqli -> prepare("select product_name from products where product_name = ? and user_id = ?");
$query -> bind_param('si', $name, $user_id);
$query -> execute();
$result = $query -> get_result();
$rows = $result -> num_rows;
$query-> close();

if ($rows == 0){
  echo "Product not found";
} else{
  $delete = $mysqli -> prepare("delete from products where product_name = ? and user_id = ?");
  $delete -> bind_param('si', $name, $user_id);
  $delete -> execute();
  $delete -> close();
  echo $name . "deleted successfuly";
}