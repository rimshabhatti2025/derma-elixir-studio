<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

$sql = "SELECT diagnosis, treatment, prescription, date FROM medical_history WHERE patient_id = '$patient_id' ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
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
        /* Medical History Section */
        .medical-history {
            max-width: 900px;
            margin: 3rem auto;
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .medical-history:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .medical-history h2 {
            text-align: center;
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Table with hover effects */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 1.2rem;
            text-align: center;
            border-bottom: 1px solid #eee;
            transition: all 0.2s ease;
        }

        th {
            background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
        }

        tr:not(:first-child):hover {
            background-color: #f8fafc;
            transform: scale(1.005);
        }

        tr:active {
            background-color: #f1f5f9;
        }

        /* Emoji styling */
        td:first-child::before {
            content: "📆 ";
        }
        td:nth-child(2)::before {
            content: "🦠 ";
        }
        td:nth-child(3)::before {
            content: "💊 ";
        }
        td:last-child::before {
            content: "📝 ";
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
            
            .medical-history {
                padding: 1.8rem;
                margin: 1.5rem;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .medical-history {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>

    <header>
        <h1>🩺 Medical History</h1>
        <nav>
            <a href="patient_dashboard.php">🏠 Home</a>
            <a href="book_appointment.php">📅 Book Appointment</a>
            <a href="view_appointments.php">📂 View Appointments</a>
            <a href="provide_feedback.php">📝 Provide Feedback</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <section class="medical-history">
        <h2>Your Medical History</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Treatment</th>
                    <th>Prescription</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['diagnosis'] ?></td>
                            <td><?= $row['treatment'] ?></td>
                            <td><?= $row['prescription'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No medical history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</body>
</html>
