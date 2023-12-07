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

    $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null; // to check if product exists
    $seller_id = isset($_POST['seller_id']) && !empty($_POST['seller_id']) ? $_POST['seller_id'] : null; // to validate seller

    // NEW VALUES
    $product_name = isset($_POST['product_name']) && !empty($_POST['product_name']) ? $_POST['product_name'] : null;
    $price = isset($_POST['price']) && !empty($_POST['price']) ? $_POST['price'] : null;
    $stock = isset($_POST['stock']) && !empty($_POST['stock']) ? $_POST['stock'] : null;

    $query = $mysqli -> prepare("select product_name, user_id from products where product_name =? and user_id = ?");
    $query -> bind_param('si', $name, $seller_id);
    $query -> execute();
    $result = $query -> get_result();
    $rows = $result -> num_rows;
    $query -> close();

    if ($rows == 0){
      echo "Product not found";

    } else {
      if ($seller_id == $decoded -> user_id){
        $query = $mysqli -> prepare("UPDATE products SET product_name = ?, price = ?, stock = ?, user_id = ? where product_name =? and user_id = ?");
        $query -> bind_param('siiisi', $product_name, $price, $stock, $decoded -> user_id, $name, $seller_id);
        $query -> execute();
        echo "Item updated";
      }
      else {
        echo "Unauthorized.";
      }
    }
  }
  else {
    $response = [];
    $response['status'] = 'unauthorized';
    echo json_encode($response);
  }
 } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
}