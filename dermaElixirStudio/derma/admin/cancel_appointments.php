<?php
include('db_connection.php');

$id = $_GET['id'];

$sql = "UPDATE appointments SET status='Cancelled' WHERE id=$id";
mysqli_query($conn, $sql);

header("Location: appointments.php");
exit();
