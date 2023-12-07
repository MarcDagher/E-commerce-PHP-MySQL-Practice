<?php 
include "./connection.php";

$username = $_POST['username'];
$email = $_POST['user_email'];
$password = $_POST['password'];
$user_type = $_POST['user_type'];

$query_names = $mysqli -> prepare("select username from users where username =? and user_type=?");
$query_names -> bind_param('ss', $username, $user_type);
$query_names -> execute();
$result = $query_names -> get_result();
$rows = $result -> num_rows;
$query_names -> close();

if ($rows > 0){
  echo "Username already taken";
}
else{
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $query_add_user = $mysqli -> prepare("insert into users(username, user_email, password, user_type) values(?,?,?,?)");
  $query_add_user -> bind_param('ssss', $username, $email, $hashed_password, $user_type);
  $query_add_user -> execute();
  $query_add_user -> close();
  $response = [];
  $response['status'] = 'user added successfully';
  echo json_encode($response);
}