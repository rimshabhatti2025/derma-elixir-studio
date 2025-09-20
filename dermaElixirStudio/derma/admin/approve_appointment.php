<?php
include('db_connection.php');

$id = $_GET['id'];

$sql = "UPDATE appointments SET status='Confirmed' WHERE id=$id";
mysqli_query($conn, $sql);

header("Location: appointments.php");
exit();
