<?php

define("START", 1);
define("END",9);
define("OBSTACLE",8);
define("EMPTY",0);

$db_server = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'maze-game';

$conn = new mysqli($db_server,$db_user,$db_password,$db_name);

// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

