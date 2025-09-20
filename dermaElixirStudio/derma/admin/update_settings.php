<?php
include("db_connection.php");

if (isset($_POST['settings'])) {
    foreach ($_POST['settings'] as $id => $value) {
        $id = intval($id);
        $value = mysqli_real_escape_string($conn, $value);
        mysqli_query($conn, "UPDATE system_settings SET setting_value = '$value' WHERE id = $id");
    }
    header("Location: system_settings.php");
    exit();
} else {
    echo "No settings received!";
}
