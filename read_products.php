<?php

include "./connection.php";

$name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null;
$user_id = isset($_POST['user_id']) && !empty($_POST['user_id']) ? $_POST['user_id'] : null;


$arr = [];
$get_data = $mysqli -> prepare("select product_name, price, stock, user_id from products");
$get_data -> execute();
$results = $get_data -> get_result();

while ($arr_results = $results -> fetch_assoc()){
  $arr[] = $arr_results;
}
$get_data -> close();

$data = [];
foreach ($arr as $key){
  $user = $mysqli -> prepare("select username from users where user_id = ?");
  $user -> bind_param('i', $key['user_id']);
  $user -> execute();
  $user -> store_result();
  $user -> bind_result($username);
  $user -> fetch();
  
  $key['user_id'] = $username;
  $user -> close();
  $data[] = $key;
}
echo json_encode($data);