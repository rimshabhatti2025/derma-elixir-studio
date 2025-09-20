<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['specialist_id'])) {
    header("Location: login.php");
    exit();
}

$specialist_id = $_SESSION['specialist_id'];
$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $prescription = $_POST['prescription'];
    $date = $_POST['date'];

    // Check if data already exists (optional, to prevent duplicate entries)
    $checkSql = "SELECT * FROM medical_history WHERE patient_id = ? AND diagnosis = ? AND treatment = ? AND prescription = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("isss", $patient_id, $diagnosis, $treatment, $prescription);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "This medical history record already exists for this patient.";
    } else {
        // Insert new medical history
        $sql = "INSERT INTO medical_history (patient_id, diagnosis, treatment, date, prescription, specialist_id)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssi", $patient_id, $diagnosis, $treatment, $date, $prescription, $specialist_id);


        if ($stmt->execute()) {
            $message = "Medical history updated successfully!";
            // Redirect to the same page to avoid resubmission on refresh
            header("Location: update_medical_history.php");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// Fetch patients who booked an appointment with this specialist
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Medical History</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        transition: all 0.3s ease;
    }
    
    header {
        background:linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
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

    form {
        background: #f9f9f9;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    form:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
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

    input[type="text"],
    input[type="date"],
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    input[type="text"]:hover,
    input[type="date"]:hover,
    select:hover {
        border-color: #3498db;
        box-shadow: 0 0 10px rgba(52, 152, 219, 0.2);
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    select:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
        transform: scale(1.01);
    }

    button[type="submit"] {
        background-color: #3498db;
        color: white;
        padding: 12px 600px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    button[type="submit"]:hover {
        background-color: #2980b9;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
    }

    button[type="submit"]:active {
        transform: translateY(0);
    }

    /* Enhanced Search Form */
    form[method="get"] {
        display: flex;
        gap: 10px;
        background: none;
        padding: 0;
        box-shadow: none;
        align-items: center;
    }

    form[method="get"] input[type="text"] {
        flex: 1;
        max-width: 500px;
        padding: 12px 20px;
        border: 2px solid #3498db;
        border-radius: 30px;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    form[method="get"] input[type="text"]:hover {
        border-color: #2980b9;
        background-color: #fff;
    }

    form[method="get"] input[type="text"]:focus {
        box-shadow: 0 0 15px rgba(52, 152, 219, 0.3);
    }

    form[method="get"] button {
        padding: 12px 25px;
        background:linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
        color: white;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    form[method="get"] button:hover {
        background:linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
    }

    form[method="get"] button:active {
        transform: translateY(0);
    }

    /* Search icon animation */
    form[method="get"] button::before {
        content: "🔍";
        transition: transform 0.3s ease;
    }

    form[method="get"] button:hover::before {
        transform: scale(1.2);
    }

    /* Message Styles */
    p[style*="color: green"] {
        background: #e8f5e9;
        padding: 10px;
        border-left: 4px solid #4caf50;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    p[style*="color: green"]:hover {
        transform: translateX(5px);
        box-shadow: 2px 0 10px rgba(76, 175, 80, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content {
            margin: 15px;
            padding: 15px;
        }
        
        form[method="get"] {
            flex-direction: column;
        }
        
        form[method="get"] input[type="text"] {
            max-width: 100%;
        }
    }
</style>
</head>
<body style="background: #fffaf0;">
<header>
    <h1>✏️ Update Medical History</h1>
    <nav>
        <a href="specialist_dashboard.php">🏠 Dashboard</a>
        <a href="view_patients.php">👨‍⚕️ View Patients</a>
        <a href="view_medical_history.php">📂 View Medical History</a>
        <a href="generate_report.php">📊 Generate Report</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
</header>

<section class="content">
    <center><h2>🩺 Enter Patient Medical History</h2></center>

    <?php if ($message): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" action="update_medical_history.php">
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
            <label>Diagnosis:</label>
            <input type="text" name="diagnosis" required>
        </div>

        <div class="form-group">
            <label>Treatment:</label>
            <input type="text" name="treatment" required>
        </div>

        <div class="form-group">
            <label>Prescription:</label>
            <input type="text" name="prescription" required>
        </div>

        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" required>
        </div>

        <center><button type="submit" style="background:linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);">➕ Add Medical History</button></center>
    </form>
</section>
</body>
</html>

<?php $conn->close(); ?>
