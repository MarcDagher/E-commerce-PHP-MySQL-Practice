<?php

$host = 'localhost';
$db_user = 'root';
$db_password = null;
$db_name = 'e-commerce';

$mysqli = new mysqli($host, $db_user, $db_password, $db_name);

if ($mysqli -> connect_error){
  die($mysqli -> connect_error);
} else{
  // echo "successul";
}