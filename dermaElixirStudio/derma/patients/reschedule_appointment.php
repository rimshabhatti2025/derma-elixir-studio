<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$appointment_id = $_GET['id'];

$sql = "SELECT * FROM appointments WHERE id = '$appointment_id'";
$result = mysqli_query($conn, $sql);
$appointment = mysqli_fetch_assoc($result);

if (!$appointment) {
    echo "Appointment not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reschedule Appointment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(168, 237, 236, 0.6), rgba(254, 214, 227, 0.79));
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background: #2c3e50;
            color: #fff;
            width: 100%;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        form {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        label {
            display: block;
            margin: 1.5rem 0 0.5rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            background: #f9f9f9;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            margin-bottom: 1rem;
        }

        input[type="date"]:hover,
        select:hover {
            border-color: #b3d7ff;
            background: #f5f9ff;
        }

        input[type="date"]:focus,
        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
            background: #fff;
        }

        select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%232c3e50" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            background-size: 12px;
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 1rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
            margin-top: 1.5rem;
        }

        button:hover {
            background: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        }

        button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        form {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 1.8rem;
                width: 85%;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<header>Derma Elixir Studio</header>
<h2>Reschedule Appointment</h2>
<form action="update_appointment.php" method="POST">
    <input type="hidden" name="appointment_id" value="<?= $appointment_id ?>">
    
    <label>New Date:</label>
    <input type="date" name="new_date" required>

    <label>New Time:</label>
    <select id="time" name="new_time" required>
        <option value="10:00 AM">10:00 AM</option>
        <option value="11:00 AM">11:00 AM</option>
        <option value="2:00 PM">2:00 PM</option>
        <option value="4:00 PM">4:00 PM</option>
    </select>

    <button type="submit">Update Appointment</button>
</form>
</body>
</html>
