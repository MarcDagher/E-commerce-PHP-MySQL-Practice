<?php 
include "./connection.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$user_role = $_POST['user_role'];

if ($user_role == "customer"){
  $id = 1;
} elseif ($user_role == "seller"){
  $id = 2;
}


$query_names = $mysqli -> prepare("select username from users where username =?");
$query_names -> bind_param('s', $username);
$query_names -> execute();
$result = $query_names -> get_result();
$rows = $result -> num_rows;
$query_names -> close();

if ($rows > 0){
  echo "Username already taken";
}
else{
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $query_add_user = $mysqli -> prepare("insert into users(username, email, password, role_id) values(?,?,?,?)");
  $query_add_user -> bind_param('ssss', $username, $email, $hashed_password, $id);
  $query_add_user -> execute();
  $query_add_user -> close();
  $response = [];
  $response['status'] = 'user added successfully';
  echo json_encode($response);
}