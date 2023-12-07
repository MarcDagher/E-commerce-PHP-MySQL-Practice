<?php

include "./connection.php";

// $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null;
// $user_id = isset($_POST['user_id']) && !empty($_POST['user_id']) ? $_POST['user_id'] : null;


$arr = [];
$get_data = $mysqli->prepare("SELECT product_name, price, username FROM products JOIN users ON products.user_id = users.user_id");

$get_data -> execute();
$results = $get_data -> get_result();

while ($arr_results = $results -> fetch_assoc()){
  $arr[] = $arr_results;
}
$get_data -> close();

foreach($arr as $info){
  echo json_encode($info) , "\n";
}