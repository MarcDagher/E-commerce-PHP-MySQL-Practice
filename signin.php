<?php
include "./connection.php";
require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

$username = $_POST['username'];
$password = $_POST['password'];

$query = $mysqli -> prepare("select username, password, role_id from users where username = ?");
$query -> bind_param('s', $username);
$query -> execute();
$query -> store_result();
$rows = $query -> num_rows;
$query -> bind_result($name, $hashed_password, $role);
$query -> fetch();
$query -> close();

$response = [];
if ($rows == 0){
  $response['status'] = 'User not found';
  echo json_encode($response);
} else {
  if (password_verify($password, $hashed_password)){
    // NOTE SHOULD I GIVE TH JWT ONLY FOR THE SELLER? TO GIVE ACCESS TO PRODUCTS AND CRUD?
    $key = "secret key";
    $payload_arr = [];
    $payload_arr["name"] = $name;
    $payload_arr["password"] = $hashed_password;
    $payload_arr["role"] = $role;

    $jwt = JWT::encode($payload_arr, $key, 'HS256');

    $response['status'] = 'Logged in';
    $response['JWT'] = $jwt;
    echo json_encode($response);
  } else {
    $response['status'] = 'Wrong credentials';
    echo json_encode($response);
  }
}