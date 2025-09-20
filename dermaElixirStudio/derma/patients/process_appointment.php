<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    die("Error: You must be logged in as a patient to book an appointment.");
}

$patient_id = $_SESSION['patient_id'];
$specialist_id = $_POST['specialist'];
$date = $_POST['date'];
$time = $_POST['time'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO appointments (patient_id, specialist_id, appointment_date, appointment_time, message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $patient_id, $specialist_id, $date, $time, $message);

if ($stmt->execute()) {
    header("Location: patient_dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
