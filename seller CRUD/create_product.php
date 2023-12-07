<?php 
include "../connection.php";

require __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = getallheaders();
if (!isset($headers['Authorization']) || empty($headers['Authorization'])){
  http_response_code(401);
  echo json_encode(["error" => "unauthorized - headers"]);
  exit();
}

$authorization_header = $headers['Authorization'];
$token = null;

$token = trim(str_replace("Bearer", '', $authorization_header));
if(!$token){
  http_response_code(401);
  echo json_encode(["error" => "unauthorized - token"]);
  exit();
}

try {
  $key = "secret key";
  $decoded = JWT::decode($token, new Key($key, 'HS256'));

  if ($decoded -> role_id == 2){

    $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null;
    $price = isset($_POST['price']) && !empty($_POST['price']) ? $_POST['price'] : null;
    $stock = isset($_POST['stock']) && !empty($_POST['stock']) ? $_POST['stock'] : null;

    // check if item already found
    $check = $mysqli -> prepare("select product_name from products where product_name = ? and user_id = ? ");
    
    $check -> bind_param('si', $name, $decoded -> user_id);
    $check -> execute();
    $result = $check -> get_result();
    $rows = $result -> num_rows;
    $check -> close();
    
    if ($rows == 0){
      $query = $mysqli -> prepare("insert into products(product_name, price, stock, user_id) values(?,?,?,?)");
      $query -> bind_param("siii", $name, $price, $stock, $decoded -> user_id);

      $query -> bind_param("siii", $name, $price, $stock, $decoded -> user_id);
      $query -> execute();
      $query -> close();
      
      $response = [];
      $response['status'] = $name . " added successully";
      echo json_encode($response);
    } elseif ($rows > 0){
      echo "Product already found";
    }
  }
  else {
    $response = [];
    $response['status'] = 'unauthorized';
    echo json_encode($response);
  }
}
catch (Exception $e) {
  http_response_code(401);
  echo json_encode(["error" => "Invalid token"]);
}