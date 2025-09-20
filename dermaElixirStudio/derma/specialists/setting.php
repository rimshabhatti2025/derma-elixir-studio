<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['specialist_id'])) {
    header("Location: ../login.php");
    exit();
}

$specialist_id = $_SESSION['specialist_id'];
$message = "";

// Fetch current specialist details
$query = "SELECT username, password, email, mobile, qualification, area_of_expertise FROM specialists WHERE id = '$specialist_id'";
$result = mysqli_query($conn, $query);
$specialist = mysqli_fetch_assoc($result);

// Update details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
    $expertise = mysqli_real_escape_string($conn, $_POST['area_of_expertise']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // plain password

    $updateQuery = "UPDATE specialists 
                    SET username='$username', email='$email', mobile='$mobile', password='$password', 
                        qualification='$qualification', area_of_expertise='$expertise' 
                    WHERE id='$specialist_id'";

    if (mysqli_query($conn, $updateQuery)) {
        $message = "✅ Profile updated successfully.";
    } else {
        $message = "❌ Failed to update profile: " . mysqli_error($conn);
    }
}

// Delete account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM specialists WHERE id = '$specialist_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        session_destroy();
        header("Location: ../login.php");
        exit();
    } else {
        $message = "❌ Failed to delete account: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Specialist Settings</title>
    <style>
        /* Keyframe animations */
        @keyframes fadeSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeScale {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(135deg, #dff6ff, #ffe4e1);
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: fadeSlideDown 0.8s ease forwards;
            margin-bottom: 70px;
        }

        header h1 {
            margin: 0;
            margin-left: 30px;
            font-size: 1.5rem;
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
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .logout:hover {
            background-color: rgba(255, 0, 0, 0.544);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
        }


        h2 {
            text-align: center;
            color: #333;
            position: relative;
            margin-bottom: 30px;
            font-size: 26px;
        }

        h2::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 3px;
            background-color: #007bff;
            left: 50%;
            transform: translateX(-50%);
            bottom: -10px;
            border-radius: 10px;
        }

        form {
            background-color: #fff;
            max-width: 500px;
            margin: auto;
            padding: 30px 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #444;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .btn {
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            width: 48%;
            margin-top: 10px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .update {
            background-color: #28a745;
            color: white;
            margin-right: 10px;
        }

        .update:hover {
            background-color: #218838;
            transform: scale(1.03);
        }

        .delete {
            background-color: #dc3545;
            color: white;
        }

        .delete:hover {
            background-color: #c82333;
            transform: scale(1.03);
        }

        .message {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <header>
        <h1><a href="#" style="color: white; text-decoration: none">⚙️ Settings</a></h1>
        <nav>
            <a href="specialist_dashboard.php">🏠 Dashboard</a>
            <a href="view_patients.php">👨‍⚕️ View Patients</a>
            <a href="view_medical_history.php">📂 View Medical History</a>
            <a href="update_medical_history.php">✏️ Update Medical History</a>
            <a href="generate_report.php">📊 Generate Report</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>
    <h2>⚙️ Account Settings</h2>
    <div class="message"><?= $message ?></div>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($specialist['username']) ?>" required>

        <label>Password</label>
        <input type="text" name="password" value="<?= htmlspecialchars($specialist['password']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($specialist['email']) ?>" required>

        <label>Mobile</label>
        <input type="text" name="mobile" value="<?= htmlspecialchars($specialist['mobile']) ?>" required>

        <label>Qualification</label>
        <input type="text" name="qualification" value="<?= htmlspecialchars($specialist['qualification']) ?>" required>

        <label>Area of Expertise</label>
        <input type="text" name="area_of_expertise" value="<?= htmlspecialchars($specialist['area_of_expertise']) ?>">

        <button type="submit" name="update" class="btn update">✅ Update</button>
        <button type="submit" name="delete" class="btn delete" onclick="return confirm('Are you sure you want to delete your account?');">🗑️ Delete Account</button>
    </form>


</body>

</html>