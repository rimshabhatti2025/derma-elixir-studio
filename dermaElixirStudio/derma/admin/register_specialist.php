<?php
include 'db_connection.php';

// Get form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$cnic = $_POST['cnic'];
$state = $_POST['state'];
$city = $_POST['city'];
$password = $_POST['password'];
$role = 'specialist';
$qualification = $_POST['qualification'];
$area_of_expertise = $_POST['area_of_expertise'];

// File upload
$profile_photo_path = '';
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $photo_tmp = $_FILES['profile_photo']['tmp_name'];
    $photo_name = basename($_FILES['profile_photo']['name']);
    $photo_ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png'];

    if (in_array($photo_ext, $allowed_ext)) {
        $new_name = uniqid('photo_', true) . '.' . $photo_ext;
        $destination = "../profile_photos/" . $new_name; // Adjust this path if needed
        if (move_uploaded_file($photo_tmp, $destination)) {
            $profile_photo_path = "profile_photos/" . $new_name;
        }
    }
}

// Insert query
$sql = "INSERT INTO specialists 
(first_name, last_name, username, email, mobile, cnic, state, city, password, qualification, area_of_expertise, role, profile_photo_path) 
VALUES 
('$first_name', '$last_name', '$username', '$email', '$mobile', '$cnic', '$state', '$city', '$password', '$qualification', '$area_of_expertise', '$role', '$profile_photo_path')";

$result = $conn->query($sql);

if ($result) {
    echo "<script>alert('Specialist added successfully!'); window.location.href='admin_dashboard.php';</script>";
} else {
    echo "❌ Error: " . $conn->error;
}

$conn->close();
