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


// eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYW1lIjoibWFyd2EiLCJwYXNzd29yZCI6IiQyeSQxMCRlSlpXU3R4SXh1WjhIbU9abGFZaGUuSUFWYnBPSUhrTzloMy5QUm1CVjBMeTFId1RTOURBQyIsInJvbGVfaWQiOjJ9.VIsHnFZDM-XA9SRuBWqUkFtYfoFNq_FyFYxpiGk2OZI