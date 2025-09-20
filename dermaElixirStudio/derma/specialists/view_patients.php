<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['specialist_id'])) {
    header("Location: login.php");
    exit();
}

$specialist_id = $_SESSION['specialist_id'];
$message = '';

// Cancel or Approve logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_appointment_id'])) {
        $appointment_id = $_POST['cancel_appointment_id'];
        $updateQuery = "UPDATE appointments SET status = 'Cancelled' WHERE id = '$appointment_id' AND specialist_id = '$specialist_id'";
        mysqli_query($conn, $updateQuery);
        $message = "Appointment cancelled successfully.";
    } elseif (isset($_POST['approve_appointment_id'])) {
        $appointment_id = $_POST['approve_appointment_id'];
        $updateQuery = "UPDATE appointments SET status = 'Confirmed' WHERE id = '$appointment_id' AND specialist_id = '$specialist_id'";
        mysqli_query($conn, $updateQuery);
        $message = "Appointment approved successfully.";
    } elseif (isset($_POST['complete_appointment_id'])) {
        $appointment_id = $_POST['complete_appointment_id'];
        $updateQuery = "UPDATE appointments SET status = 'Completed' WHERE id = '$appointment_id' AND specialist_id = '$specialist_id'";
        mysqli_query($conn, $updateQuery);
        $message = "Appointment marked as completed.";
    } elseif (isset($_POST['reschedule_appointment_id']) && isset($_POST['new_date']) && isset($_POST['new_time'])) {
        $appointment_id = $_POST['reschedule_appointment_id'];
        $new_date = $_POST['new_date'];
        $new_time = $_POST['new_time'];

        $updateQuery = "UPDATE appointments SET appointment_date = ?, appointment_time = ? WHERE id = ? AND specialist_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssii", $new_date, $new_time, $appointment_id, $specialist_id);
        $stmt->execute();

        $message = "Appointment rescheduled successfully.";
    }
}

// Search handling
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$query = "
    SELECT 
        a.id AS appointment_id,
        a.status,
        a.appointment_date,
        a.appointment_time,
        p.first_name, 
        p.last_name, 
        p.email, 
        p.certificate_verified
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.id
    WHERE a.specialist_id = '$specialist_id'
";
if (!empty($search)) {
    $query .= " AND (p.first_name LIKE '%$search%' OR p.last_name LIKE '%$search%')";
}
// Order appointments by date and time in ascending order
$query .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View My Patients</title>
    <link rel="stylesheet" href="specialiststyles.css">
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(135deg, #dff6ff, #ffe4e1);
            min-height: 100vh;
        }

        /* Consistent Header Styling */
        header {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* Message Styling */
        .message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 5px;
            border-left: 4px solid #4caf50;
            transition: all 0.3s ease;
        }

        .message:hover {
            transform: translateX(5px);
            box-shadow: 2px 0 10px rgba(76, 175, 80, 0.2);
        }

        /* Search Box Styling */
        .search-box {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .search-box form {
            display: flex;
            gap: 10px;
        }

        .search-box input[type="text"] {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #3498db;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .search-box input[type="text"]:hover {
            border-color: #2980b9;
            background-color: #fff;
        }

        .search-box input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.3);
        }

        .search {
            padding: 12px 25px;
            background-color: #7a6b8e;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search:hover {
            background-color: #4a6583;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
        }

        /* Table Styling */
        h2 {
            color: #2c3e50;
            margin: 30px auto 20px;
            max-width: 1200px;
            padding: 0 20px;
        }

        table {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto 30px;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        th:hover {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        tr:hover td {
            background-color: #f5f9ff;
            transform: scale(1.01);
        }

        /* Button Styling */
        .btn-approve,
        .btn-cancel {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-approve {
            background-color: #4caf50;
            color: white;
        }

        .btn-approve:hover:not(:disabled) {
            background-color: #3d8b40;
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(76, 175, 80, 0.3);
        }

        .btn-cancel {
            background-color: #f44336;
            color: white;
        }

        .btn-cancel:hover:not(:disabled) {
            background-color: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(244, 67, 54, 0.3);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-complete {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 7px 10px;
            margin-top: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-complete:disabled {
            background-color: #b3d1ff;
            cursor: not-allowed;
        }

        .btn-reschedule {
            background-color: #ff9800;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-reschedule:hover {
            background-color: #e68a00;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem 0.5rem;
            }

            header h1 {
                margin: 10px 0;
            }

            nav {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 10px;
            }

            .logout {
                margin-right: 0;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .search-box form {
                flex-direction: column;
            }

            .search {
                width: 100%;
            }
        }
    </style>
</head>

<body style="background: linear-gradient(135deg, #dff6ff, #ffe4e1);">

    <header>
        <h1 style="color:white">👨‍⚕️ My Patients</h1>
        <nav>
            <a href="specialist_dashboard.php">🏠 Dashboard</a>
            <a href="view_medical_history.php">📂 View Medical History</a>
            <a href="update_medical_history.php">✏️ Update Medical History</a>
            <a href="generate_report.php">📊 Generate Report</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="search-box">
        <form method="get">
            <input type="text" name="search" placeholder="Search by patient name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="search">🔍 Search</button>
        </form>
    </div>

    <h2>Patients Who Booked Appointments With You</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Appointment Date</th>
            <th>Certificate Verified</th>
            <th>Status</th>
            <th>Actions</th> <!-- only Approve, Cancel, Complete buttons -->
            <th>Reschedule</th> <!-- separate reschedule column -->
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['appointment_date']) ?: 'Not set' ?></td>
                <td><?= $row['certificate_verified'] ? '✅ Yes' : '❌ No' ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>

                <!-- Actions Column -->
                <td>
                    <!-- Approve Button -->
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="approve_appointment_id" value="<?= $row['appointment_id'] ?>">
                        <button type="submit" class="btn-approve" <?= in_array($row['status'], ['Confirmed', 'Completed', 'Cancelled']) ? 'disabled' : '' ?>>Approve</button>
                    </form>

                    <!-- Cancel Button -->
                    <form method="post" style="display:inline-block;" onsubmit="return confirm('Cancel this appointment?');">
                        <input type="hidden" name="cancel_appointment_id" value="<?= $row['appointment_id'] ?>">
                        <button type="submit" class="btn-cancel" <?= $row['status'] === 'Cancelled' ? 'disabled' : '' ?>>Cancel</button>
                    </form>

                    <!-- Complete Button -->
                    <form method="post" style="display:inline-block;" onsubmit="return confirm('Mark this appointment as completed?');">
                        <input type="hidden" name="complete_appointment_id" value="<?= $row['appointment_id'] ?>">
                        <button type="submit" class="btn-complete" <?= $row['status'] !== 'Confirmed' ? 'disabled' : '' ?>>Completed</button>
                    </form>
                </td>

                <!-- Reschedule Column -->
                <td>
                    <form method="post">
                        <input type="hidden" name="reschedule_appointment_id" value="<?= $row['appointment_id'] ?>">
                        <input type="date" name="new_date" required style="margin-bottom:5px;">
                        <input type="time" name="new_time" required style="margin-bottom:5px;">
                        <button type="submit" class="btn-reschedule">Reschedule</button>
                    </form>
                </td>

            </tr>
        <?php endwhile; ?>
    </table>


</body>

</html>

<?php mysqli_close($conn); ?>