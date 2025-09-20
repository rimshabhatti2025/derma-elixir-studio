<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'test';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>