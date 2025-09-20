<?php
session_start();
include 'db_connection.php';

// Fetch treatments
$treatments = [];
$treatment_sql = "SELECT id, name FROM treatments";
$treatment_result = mysqli_query($conn, $treatment_sql);

if ($treatment_result && mysqli_num_rows($treatment_result) > 0) {
    while ($row = mysqli_fetch_assoc($treatment_result)) {
        $treatments[] = $row;
    }
}

// Fetch specialists
$specialists = [];
$specialist_sql = "SELECT id, first_name, last_name FROM specialists";
$specialist_result = mysqli_query($conn, $specialist_sql);

if ($specialist_result && mysqli_num_rows($specialist_result) > 0) {
    while ($row = mysqli_fetch_assoc($specialist_result)) {
        $specialists[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <style>
        /* General Page Styling */
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
        /* Page Heading */
        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.2rem;
            margin: 1.5rem 0;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Form Styling */
        form {
            max-width: 600px;
            width: 90%;
            margin: 20px auto;
            padding: 2.5rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        /* Labels */
        form label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }

        /* Input Fields */
        form input[type="text"],
        form input[type="email"],
        form input[type="tel"],
        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 0.9rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            color: #333;
            background: #f9f9f9;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        form input:hover,
        form select:hover,
        form textarea:hover {
            border-color: #b3d7ff;
            background: #f5f9ff;
        }

        form input:focus,
        form select:focus,
        form textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
            background: #fff;
        }

        /* Select Dropdown */
        form select {
            appearance: none;
            background: #f9f9f9 url('data:image/svg+xml;utf8,<svg fill="%232c3e50" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 0.8rem center;
            background-size: 12px;
            cursor: pointer;
        }

        /* Textarea */
        form textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Submit Button */
        form button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        form button:hover {
            background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
        }

        form button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }
            
            header h1 {
                margin-left: 0;
                font-size: 1.5rem;
            }
            
            nav {
                margin-right: 0;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            form {
                padding: 1.8rem;
            }
            
            h1 {
                font-size: 1.8rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        form {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <header>
        <h1><a style="color:white;text-decoration:none">📅 Book Appointment</a></h1>
        <nav>
            <a href="patient_dashboard.php">🏠 Home</a>
            <a href="view_appointments.php">📂 View Appointments</a>
            <a href="medical_history.php">🩺 Medical History</a>
            <a href="provide_feedback.php">📝 Provide Feedback</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>
    
    <h1>𝐁𝐨𝐨𝐤 𝐚𝐧 𝐀𝐩𝐩𝐨𝐢𝐧𝐭𝐦𝐞𝐧𝐭 𝐍𝐨𝐰!</h1>
    
    <form action="process_appointment.php" method="post">
        <label for="treatment">Select Treatment:</label>
        <select id="treatment" name="treatment" required onchange="fetchSpecialists()">
            <option value="" disabled selected>Select a treatment</option>
            <?php
            foreach ($treatments as $treatment) {
                echo "<option value='{$treatment['id']}'>{$treatment['name']}</option>";
            }
            ?>
        </select> 

        <label for="specialist">Select Specialist:</label>
        <select id="specialist" name="specialist" required>
            <option value="" disabled selected>Select a specialist</option>
            <?php
            foreach ($specialists as $specialist) {
                echo "<option value='{$specialist['id']}'>{$specialist['first_name']} {$specialist['last_name']}</option>";
            }
            ?>
        </select>

        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="time">Select Time:</label>
        <select id="time" name="time" required>
            <option value="10:00 AM">10:00 AM</option>
            <option value="12:00 AM">12:00 PM</option>
            <option value="2:00 PM">2:00 PM</option>
            <option value="4:00 PM">4:00 PM</option>
            <option value="6:00 PM">6:00 PM</option>
            <option value="8:00 PM">8:00 PM</option>
        </select>

        <label for="message">Reason for Appointment:</label>
        <textarea id="message" name="message"></textarea>

        <button type="submit">📅 Confirm Appointment</button>
    </form>
</body>
</html>