<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];
$message = '';

// Update details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plain text password

    $updateQuery = "UPDATE patients 
                    SET username='$username', email='$email', mobile='$mobile', password='$password' 
                    WHERE id='$patient_id'";

    if (mysqli_query($conn, $updateQuery)) {
        $message = "✅ Profile updated successfully.";
    } else {
        $message = "❌ Failed to update profile: " . mysqli_error($conn);
    }
}


// Delete account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM patients WHERE id='$patient_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        session_destroy();
        header("Location: ../login.php?deleted=1");
        exit();
    } else {
        $message = "❌ Failed to delete account: " . mysqli_error($conn);
    }
}

// Fetch current details
$fetchQuery = "SELECT username, email, mobile FROM patients WHERE id='$patient_id'";
$result = mysqli_query($conn, $fetchQuery);
$patient = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <title>⚙️ Settings</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            transition: all 0.3s ease;
        }

        header {
            background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            line-height: 30px;
            margin-bottom: 70px;
        }

        header h1 {
            margin: 0;
            margin-left: 30px;
            transition: transform 0.3s ease;
        }

        header h1:hover {
            transform: translateX(5px);
        }

        nav {
            display: flex;
            gap: 1rem;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
        }

        nav a:not(.logout):hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        nav a:not(.logout)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #fff;
            transition: all 0.3s ease;
        }

        nav a:not(.logout):hover::after {
            width: 70%;
            left: 15%;
        }

        .logout {
            background-color: rgba(255, 0, 0, 0.762);
            margin-right: 30px;
            padding-left: 25px;
            padding-right: 25px;
            transition: all 0.3s ease;
        }

        .logout:hover {
            background-color: rgba(255, 0, 0, 0.544);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            padding: 30px 40px;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.5rem;
            color: #111827;
            font-weight: 600;
        }

        label {
            padding: 30px 10px;
            font-size: 18px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 12px 0;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            font-size: 1rem;
            transition: border 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        input[type="submit"] {
            width: 48%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .update {
            background-color: #10b981;
            color: white;
        }

        .update:hover {
            background-color: #059669;
            transform: translateY(-2px);
        }

        .delete {
            background-color: #ef4444;
            color: white;
            float: right;
        }

        .delete:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
        }

        .msg {
            text-align: center;
            margin-top: 15px;
            font-weight: 500;
            color: #374151;
        }
    </style>
</head>

<body>
    <header>
        <h1><a href="#" style="color: white; text-decoration:none;">⚙️ Settings</a></h1>
        <nav>
            <a href="patient_dashboard.php">🏠 Home</a>
            <a href="book_appointment.php">📅 Book Appointment</a>
            <a href="medical_history.php">🩺 Medical History</a>
            <a href="download_report.php">📄 Download Report</a> <!-- New -->
            <a href="provide_feedback.php">📝 Provide Feedback</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2>⚙️ Account Settings</h2>

        <?php if ($message): ?>
            <p class="msg"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label style="font-weight: bold;">Username:</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($patient['username']) ?>">

            <label style="font-weight: bold;">Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($patient['email']) ?>">

            <label style="font-weight: bold;">Mobile:</label>
            <input type="text" name="mobile" required value="<?= htmlspecialchars($patient['mobile']) ?>">

            <label style="font-weight: bold;">New Password:</label>
            <input type="password" name="password" required placeholder="Enter new password">

            <input type="submit" name="update" class="update" value="Update">
            <input type="submit" name="delete" class="delete" value="Delete Account" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
        </form>
    </div>
</body>

</html>