<?php
include 'db_connection.php';

// Get form data
$first_name = $_POST['first-name'];
$last_name = $_POST['last-name'];
$username = $_POST['username'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$cnic = $_POST['cnic'];
$state = $_POST['state'];
$city = $_POST['city'];
$password = $_POST['password'];
$role = 'patient';

// ===== Certificate Upload =====
$target_dir = "certificates/";
$certificate_name = basename($_FILES["certificate"]["name"]);
$unique_certificate = time() . "_" . $certificate_name;
$certificate_path = $target_dir . $unique_certificate;

$certificate_type = strtolower(pathinfo($certificate_path, PATHINFO_EXTENSION));
$allowed_certificate_types = ['pdf', 'jpg', 'jpeg', 'png'];

if (!in_array($certificate_type, $allowed_certificate_types)) {
    echo "<script>alert('❌ Only PDF, JPG, JPEG, or PNG files are allowed for the certificate.'); window.history.back();</script>";
    exit;
}

// ===== Profile Photo Upload =====
$photo_dir = "profile_photos/";
$photo_name = basename($_FILES["profile-photo"]["name"]);
$unique_photo = time() . "_" . $photo_name;
$photo_path = $photo_dir . $unique_photo;

$photo_type = strtolower(pathinfo($photo_path, PATHINFO_EXTENSION));
$allowed_photo_types = ['jpg', 'jpeg', 'png'];

if (!in_array($photo_type, $allowed_photo_types)) {
    echo "<script>alert('❌ Only JPG, JPEG, or PNG files are allowed for the profile photo.'); window.history.back();</script>";
    exit;
}

// ===== Upload both files =====
if (
    move_uploaded_file($_FILES["certificate"]["tmp_name"], $certificate_path) &&
    move_uploaded_file($_FILES["profile-photo"]["tmp_name"], $photo_path)
) {

    // Save user info and both file paths to the database
    $sql = "INSERT INTO patients 
        (first_name, last_name, username, email, mobile, cnic, state, city, password, role, certificate_path, profile_photo_path) 
        VALUES 
        ('$first_name', '$last_name', '$username', '$email', '$mobile', '$cnic', '$state', '$city', '$password', '$role', '$certificate_path', '$photo_path')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('❌ Error saving to database.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('❌ File upload failed.'); window.history.back();</script>";
}

$conn->close();
