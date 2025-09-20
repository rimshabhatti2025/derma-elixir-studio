<?php
session_start();
if (!isset($_SESSION['specialist_id'])) {
    header("Location: ../login.php");
    exit();
}

include 'db_connection.php';

$specialist_id = $_SESSION['specialist_id'];

// Fetch specialist info including photo
$sql = "SELECT first_name, last_name, profile_photo_path FROM specialists WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$specialist = $result->fetch_assoc();

// ✅ Set profile photo path
$profilePath = $specialist['profile_photo_path'] ?? '';
$fullPath = "../" . $profilePath;
$profileSrc = (!empty($profilePath) && file_exists($fullPath))
    ? htmlspecialchars($fullPath)
    : '../profile_photos/default_avatar.png';

// Fetch total appointments
$totalAppointmentsQuery = "SELECT COUNT(*) AS total_appointments FROM appointments WHERE specialist_id = ?";
$stmt = $conn->prepare($totalAppointmentsQuery);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$totalAppointments = $stmt->get_result()->fetch_assoc()['total_appointments'];

// Fetch total medical history entries
$medicalHistoryQuery = "
    SELECT COUNT(*) AS total_history 
    FROM medical_history mh
    JOIN appointments a ON mh.patient_id = a.patient_id
    WHERE a.specialist_id = ?
";
$stmt = $conn->prepare($medicalHistoryQuery);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$medicalHistoryCount = $stmt->get_result()->fetch_assoc()['total_history'];

// Fetch total reports
$reportsQuery = "SELECT COUNT(*) AS total_reports FROM reports WHERE specialist_id = ?";
$stmt = $conn->prepare($reportsQuery);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$reportsCount = $stmt->get_result()->fetch_assoc()['total_reports'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Skin Specialist Dashboard - Derma Elixir Studio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="specialiststyles.css">
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

        .specialist-welcome {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            animation: fadeIn 1s ease;
        }

        .welcome-content {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .welcome-content img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        section {
            padding: 40px;
            text-align: center;
        }

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .dashboard-box {
            margin-top: 70px;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 250px;
            transition: transform 0.3s ease;
            animation: fadeScale 0.8s ease forwards;
        }

        .dashboard-box:hover {
            transform: scale(1.05);
        }

        .dashboard-box h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #2c3e50;
        }

        .dashboard-box p {
            font-size: 18px;
            color: #555;
        }
    </style>

</head>

<body>

    <header>
        <h1><a href="#" style="color: white; text-decoration: none">👨‍⚕️ Skin Specialist Dashboard</a></h1>
        <nav>
            <a href="view_patients.php">👩‍⚕️ View Patients</a>
            <a href="view_medical_history.php">📂 View Medical History</a>
            <a href="search.php">🔍 Search</a> <!-- New -->
            <a href="generate_report.php">📊 Generate Report</a>
            <a href="setting.php">⚙️ Settings</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <section>
        <div class="specialist-welcome">
            <div class="welcome-content">
                <img src="<?= $profileSrc ?>" alt="Profile Photo">
                <div>
                    <h2>Welcome, <?= htmlspecialchars($specialist['first_name'] . " " . $specialist['last_name']) ?>!</h2>
                    <p>Select an action from the navigation menu above.</p>
                </div>
            </div>
        </div>



        <div class="dashboard-container">
            <div class="dashboard-box">
                <h3>📅 Total Appointments</h3>
                <p><?= $totalAppointments ?> appointments</p>
            </div>
            <div class="dashboard-box">
                <h3>🩺 Medical History Entries</h3>
                <p><?= $medicalHistoryCount ?> records</p>
            </div>
            <div class="dashboard-box">
                <h3>🧾 Reports Generated</h3>
                <p><?= $reportsCount ?> reports</p>
            </div>
        </div>
    </section>

</body>

</html>

<?php $conn->close(); ?>