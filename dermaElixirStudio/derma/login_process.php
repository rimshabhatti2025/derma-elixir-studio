<?php
error_reporting(0);
session_start();

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['username'];
    $pass = $_POST['password'];

    // Check in patients table
    $sql = "SELECT id, username FROM patients WHERE username = '$name' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['username'] = $name;
        $_SESSION['patient_id'] = $row['id'];
        header("location: patients/patient_dashboard.php");
        exit();
    }

    // Check in specialists table
    $sql = "SELECT id, username FROM specialists WHERE username = '$name' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['username'] = $name;
        $_SESSION['specialist_id'] = $row['id'];
        header("location: specialists/specialist_dashboard.php");
        exit();
    }

    // Check in admins table
    $sql = "SELECT id, username FROM admins WHERE username = '$name' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['username'] = $name;
        $_SESSION['admin_id'] = $row['id'];
        header("location: admin/admin_dashboard.php");
        exit();
    }

    $_SESSION['loginmessage'] = "Wrong details, Try again...";
    header("location: login.php");
    exit();
}
