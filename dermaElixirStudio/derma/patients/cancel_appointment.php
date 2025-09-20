<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Delete the appointment
    $deleteQuery = "DELETE FROM appointments WHERE id = '$appointment_id' AND patient_id = '{$_SESSION['patient_id']}'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: view_appointments.php?message=Appointment Canceled");
    } else {
        echo "Error canceling appointment: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>
