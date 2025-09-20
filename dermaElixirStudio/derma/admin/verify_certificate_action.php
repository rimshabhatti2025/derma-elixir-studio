<?php
include("db_connection.php");

if (isset($_POST['verify'])) {
    $id = intval($_POST['patient_id']);

    $sql = "UPDATE patients SET certificate_verified = 1 WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: verify_certificates.php");
        exit();
    } else {
        echo "❌ Error verifying certificate: " . mysqli_error($conn);
    }
}
