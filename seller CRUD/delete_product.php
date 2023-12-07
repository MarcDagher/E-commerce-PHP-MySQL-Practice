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
  print_r($decoded);

  if ($decoded -> role_id == 2){
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

  } else {
    $response = [];
    $response['status'] = 'unauthorized';
    echo json_encode($response);
  }
}
catch (Exception $e) {
  http_response_code(401);
  echo json_encode(["error" => "Invalid token"]);
}