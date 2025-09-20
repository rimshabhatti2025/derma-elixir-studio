<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['specialist_id'])) {
    header("Location: login.php");
    exit();
}

$specialist_id = $_SESSION['specialist_id'];
$message = '';

// Handle report submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $report_details = $_POST['report_details'];
    $prescribed_medication = $_POST['prescribed_medication'];
    $treatment = $_POST['treatment'];
    $lab_tests = $_POST['lab_tests'];
    $report_date = $_POST['report_date'];

    // Insert the report into the database
    $sql = "INSERT INTO reports (specialist_id, patient_id, report_date, report_details, prescribed_medication, treatment, lab_tests)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssss", $specialist_id, $patient_id, $report_date, $report_details, $prescribed_medication, $treatment, $lab_tests);

    if ($stmt->execute()) {
        $message = "Report generated successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// Fetch patients associated with the logged-in specialist
$patientQuery = "
    SELECT DISTINCT p.id, p.first_name, p.last_name 
    FROM patients p
    JOIN appointments a ON p.id = a.patient_id
    WHERE a.specialist_id = ?
";
$stmt = $conn->prepare($patientQuery);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$patients = $stmt->get_result();

// ✅ 1. Query the Reports Data
$reportQuery = "
    SELECT r.*, p.first_name, p.last_name 
    FROM reports r
    JOIN patients p ON r.patient_id = p.id
    WHERE r.specialist_id = ?
    ORDER BY r.report_date DESC
";
$stmt = $conn->prepare($reportQuery);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$reportResults = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report - Derma Elixir Studio</title>
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
            padding:1.5rem;
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
            padding: 0.7rem 1rem;
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


        /* Main Content Styles */
        section {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            transition: all 0.3s ease;
        }

        h2:hover {
            letter-spacing: 1px;
            border-bottom-color: #2980b9;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .form-group:hover {
            transform: translateX(5px);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .form-group:hover label {
            color: #3498db;
        }

        select,
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        select:hover,
        input[type="date"]:hover,
        textarea:hover {
            border-color: #3498db;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.1);
        }

        select:focus,
        input[type="date"]:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.2);
            transform: scale(1.01);
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* Message Styles */
        p[style*="color: green"] {
            background: #e8f5e9;
            padding: 15px;
            border-left: 4px solid #4caf50;
            margin-bottom: 25px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        p[style*="color: green"]:hover {
            transform: translateX(5px);
            box-shadow: 2px 0 10px rgba(76, 175, 80, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem 0.5rem;
                text-align: center;
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

            section {
                margin: 15px;
                padding: 20px;
            }
        }
    </style>
</head>

<body style="background: linear-gradient(135deg, #dff6ff, #ffe4e1);">

    <header>
        <h1><a href="#" style="color: white; text-decoration:none;">📊 Generate Report</a></h1>
        <nav>
            <a href="specialist_dashboard.php">🏠 Dashboard</a>
            <a href="view_patients.php">👨‍⚕️ View Patients</a>
            <a href="view_medical_history.php">📂 View Medical History</a>
            <a href="update_medical_history.php">✏️ Update Medical History</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <section style="padding: 20px; text-align: center;">
        <h2>📝 Generate Patient Report</h2>

        <?php if ($message): ?>
            <p style="color: green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="post" action="generate_report.php">
            <div class="form-group">
                <label>Select Patient:</label>
                <select name="patient_id" required>
                    <option value="">-- Select Patient --</option>
                    <?php while ($row = $patients->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>">
                            <?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Report Details:</label>
                <textarea name="report_details" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Prescribed Medication:</label>
                <textarea name="prescribed_medication" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Treatment:</label>
                <textarea name="treatment" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Lab Tests (if any):</label>
                <textarea name="lab_tests" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Report Date:</label>
                <input type="date" name="report_date" value="<?= date('Y-m-d') ?>" required>
            </div>

            <button type="submit">Generate Report</button>
        </form>
    </section>

        <?php if ($reportResults->num_rows > 0): ?>
            <h2 style="margin: 50px;">📋 Generated Reports</h2>
            <div style="overflow-x:auto;">
                <table border="1" cellpadding="10" cellspacing="0"
                    style="width:1600px; border-collapse: collapse; margin-left: 40px;margin-right: 40px; margin-bottom: 40px;border: 1px solid #ccc;">

                    <thead style="background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%); color: white;">
                        <tr>
                            <th>Patient Name</th>
                            <th>Report Date</th>
                            <th>Report Details</th>
                            <th>Prescribed Medication</th>
                            <th>Treatment</th>
                            <th>Lab Tests</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($report = $reportResults->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($report['first_name'] . ' ' . $report['last_name']) ?></td>
                                <td><?= htmlspecialchars($report['report_date']) ?></td>
                                <td><?= nl2br(htmlspecialchars($report['report_details'])) ?></td>
                                <td><?= nl2br(htmlspecialchars($report['prescribed_medication'])) ?></td>
                                <td><?= nl2br(htmlspecialchars($report['treatment'])) ?></td>
                                <td><?= nl2br(htmlspecialchars($report['lab_tests'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="margin-top: 30px;">No reports generated yet.</p>
        <?php endif; ?>
</body>

</html>

<?php $conn->close(); ?>