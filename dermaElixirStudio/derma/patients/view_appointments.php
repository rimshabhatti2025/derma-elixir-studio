<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch all appointments
$sql = "SELECT a.id, s.first_name, s.last_name, a.appointment_date, a.appointment_time, a.status 
        FROM appointments a
        JOIN specialists s ON a.specialist_id = s.id
        WHERE a.patient_id = '$patient_id'
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$result = mysqli_query($conn, $sql);

// Fetch total appointment count
$totalAppointments = mysqli_num_rows($result);

// Fetch upcoming appointment
$upcoming_sql = "SELECT s.first_name, s.last_name, a.appointment_date, a.appointment_time 
                 FROM appointments a
                 JOIN specialists s ON a.specialist_id = s.id
                 WHERE a.patient_id = '$patient_id' AND a.appointment_date >= CURDATE()
                 ORDER BY a.appointment_date ASC, a.appointment_time ASC LIMIT 1";

$upcoming_result = mysqli_query($conn, $upcoming_sql);
$upcoming = mysqli_fetch_assoc($upcoming_result);

// Fetch last appointment
$last_sql = "SELECT s.first_name, s.last_name, a.appointment_date, a.appointment_time 
             FROM appointments a
             JOIN specialists s ON a.specialist_id = s.id
             WHERE a.patient_id = '$patient_id'
             ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT 1";

$last_result = mysqli_query($conn, $last_sql);
$last = mysqli_fetch_assoc($last_result);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <!-- <link rel="stylesheet" href="view_appointments.css"> -->
    <style>
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
/* Summary Cards (matching dashboard) */
.summary-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    margin: 2rem auto;
    padding: 0 1rem;
    max-width: 1200px;
}

.top-row {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    width: 100%;
}

.bottom-row {
    display: flex;
    justify-content: center;
    width: 100%;
}

.summary-card {
    background: white;
    padding: 1.8rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    text-align: center;
    width: 100%;
    max-width: 400px;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border: 1px solid rgba(0,0,0,0.05);
    margin: 20px;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    border-color: rgba(52, 152, 219, 0.3);
}

.summary-card h3 {
    margin-bottom: 1rem;
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
    position: relative;
    padding-bottom: 0.8rem;
}

.summary-card h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: #3498db;
    border-radius: 3px;
}

.summary-card p {
    margin: 0.8rem 0;
    color: #555;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.summary-card:hover p {
    color: #333;
}

/* Appointments Section (matching dashboard) */
.appointments {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin: 2rem auto;
    max-width: 95%;
}

.appointments h2 {
    text-align: center;
    color: #2c3e50;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

table th, table td {
    padding: 1rem;
    text-align: center;
    border-bottom: 1px solid #eee;
    transition: all 0.2s ease;
}


table th {
    background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

table tr:not(:first-child):hover {
    background-color: #f8fafc;
    transform: scale(1.005);
}

table tr:active {
    background-color: #f1f5f9;
}

/* Status Badges - Unified Styling */
.pending, .approved, .canceled {
    display: inline-flex;       /* Better alignment than inline-block */
    align-items: center;        /* Vertically center content */
    justify-content: center;    /* Horizontally center content */
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    min-width: 90px;
    margin: 0 auto;             /* Center in the cell */
    transition: all 0.3s ease;  /* Smooth hover effects */
}

.pending {
    background-color: #FFF3CD;
    color: #856404;
}

.approved {
    background-color: #D4EDDA;
    color: #155724;
}

.canceled {
    background-color: #F8D7DA;
    color: #721C24;
}

/* Ensure all table cells center content */
table td {
    text-align: center;         /* Horizontal alignment */
    vertical-align: middle;    /* Vertical alignment */
}
/* Buttons (enhanced version) */
.reschedule, .cancel {
    padding: 0.6rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin: 0.2rem;
}

.reschedule {
    background: #4CAF50;
    color: white;
}

.reschedule:hover {
    background: #3e8e41;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
}

.reschedule:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(76, 175, 80, 0.3);
}

.cancel {
    background: #FF5252;
    color: white;
}

.cancel:hover {
    background: #e53935;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 82, 82, 0.3);
}

.cancel:active {
    transform: translateY(0);
    box-shadow: 0 1px 3px rgba(255, 82, 82, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        padding: 1rem;
        gap: 1rem;
    }
    
    nav {
        flex-wrap: wrap;
        justify-content: center;
        margin-right: 0;
    }
    
    .summary-container {
        flex-direction: column;
        align-items: center;
    }
    
    .summary-card {
        width: 100%;
        max-width: 350px;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
    
    .reschedule, .cancel {
        padding: 0.5rem 0.8rem;
        font-size: 0.8rem;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.summary-card, .appointments {
    animation: fadeIn 0.5s ease-out forwards;
}

.appointments {
    animation-delay: 0.1s;
}
    </style>
</head>
<body>

<header>
    <h1>📂 View Appointments</h1>
    <nav>
        <a href="patient_dashboard.php">🏠 Home</a>
        <a href="book_appointment.php">📅 Book Appointment</a>
        <a href="medical_history.php">🩺 Medical History</a>
        <a href="provide_feedback.php">📝 Provide Feedback</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
</header>

<!-- Summary Cards Section - Updated Structure -->
<section class="summary-container">
    <div class="top-row">
        <div class="summary-card">
            <h3>Total Appointments</h3>
            <p>📅 <?= $totalAppointments ?></p>
        </div>

        <div class="summary-card">
            <h3>Upcoming Appointment</h3>
            <?php if ($upcoming): ?>
                <p>👨‍⚕️ <?= $upcoming['first_name'] . " " . $upcoming['last_name'] ?></p>
                <p>📅 <?= $upcoming['appointment_date'] ?> | ⏰ <?= $upcoming['appointment_time'] ?></p>
            <?php else: ?>
                <p>No upcoming appointments</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="bottom-row">
        <div class="summary-card">
            <h3>Last Appointment</h3>
            <?php if ($last): ?>
                <p>👨‍⚕️ <?= $last['first_name'] . " " . $last['last_name'] ?></p>
                <p>📅 <?= $last['appointment_date'] ?> | ⏰ <?= $last['appointment_time'] ?></p>
            <?php else: ?>
                <p>No past appointments</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Appointments Table -->
<section class="appointments">
    <center><h2>Your Appointments</h2></center>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Specialist</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>👨‍⚕️ <?= $row['first_name'] . " " . $row['last_name'] ?></td>
                    <td>📅 <?= $row['appointment_date'] ?></td>
                    <td>⏰ <?= $row['appointment_time'] ?></td>
                    <td style="margin: 20px" class="<?= strtolower($row['status']) ?>">📌 <?= ucfirst($row['status']) ?></td>
                    <td>
    <form action="reschedule_appointment.php" method="GET" style="display:inline;">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit" class="reschedule">🔄 Reschedule</button>
    </form>
    
    <form action="cancel_appointment.php" method="POST" style="display:inline;">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit" class="cancel" onclick="return confirm('Are you sure you want to cancel this appointment?')">❌ Cancel</button>
    </form>
</td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle Reschedule Button
        document.querySelectorAll(".reschedule").forEach(button => {
            button.addEventListener("click", function () {
                let appointmentId = this.closest("tr").querySelector("td:first-child").innerText;
                window.location.href = "reschedule_appointment.php?id=" + appointmentId;
            });
        });

        // Handle Cancel Button
        document.querySelectorAll(".cancel").forEach(button => {
            button.addEventListener("click", function () {
                let appointmentId = this.closest("tr").querySelector("td:first-child").innerText;

                if (confirm("Are you sure you want to cancel this appointment?")) {
                    window.location.href = "cancel_appointment.php?id=" + appointmentId;
                }
            });
        });
    });
</script>
</body>
</html>
