<?php
include "./connection.php";
require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

$username = $_POST['username'];
$password = $_POST['password'];

$query = $mysqli -> prepare("select user_id, username, password, role_id from users where username = ?");
$query -> bind_param('s', $username);
$query -> execute();
$query -> store_result();
$rows = $query -> num_rows;
$query -> bind_result($user_id, $name, $hashed_password, $role_id);
$query -> fetch();
$query -> close();

$response = [];
if ($rows == 0){
  $response['status'] = 'User not found';
  echo json_encode($response);
} else {
  if (password_verify($password, $hashed_password)){

    $key = "secret key";
    $payload_arr = [];
    $payload_arr["user_id"] = $user_id;
    $payload_arr["name"] = $name;
    $payload_arr["password"] = $hashed_password;
    $payload_arr["role_id"] = $role_id;

    $jwt = JWT::encode($payload_arr, $key, 'HS256');
    $response['status'] = 'Logged in';
    $response['JWT'] = $jwt;
    echo json_encode($response);
  } else {
    $response['status'] = 'Wrong credentials';
  }
}