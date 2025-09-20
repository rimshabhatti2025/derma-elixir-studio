<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['appointment_id']) || !isset($_POST['new_date']) || !isset($_POST['new_time'])) {
    echo "Invalid request!";
    exit();
}

$appointment_id = $_POST['appointment_id'];
$new_date = $_POST['new_date'];
$new_time = $_POST['new_time'];

$sql = "UPDATE appointments SET appointment_date = '$new_date', appointment_time = '$new_time' WHERE id = '$appointment_id'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Appointment Rescheduled Successfully!'); window.location.href='view_appointments.php';</script>";
} else {
    echo "<script>alert('Error Rescheduling Appointment!'); window.location.href='view_appointments.php';</script>";
}

$conn->close();
?>
