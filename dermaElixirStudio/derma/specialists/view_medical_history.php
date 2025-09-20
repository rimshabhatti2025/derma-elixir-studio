<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['specialist_id'])) {
    header("Location: login.php");
    exit();
}

$specialist_id = $_SESSION['specialist_id'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "
    SELECT mh.*, p.first_name, p.last_name
    FROM medical_history mh
    JOIN patients p ON mh.patient_id = p.id
    JOIN appointments a ON p.id = a.patient_id
    WHERE a.specialist_id = ?
";

if ($search !== '') {
    $sql .= " AND (p.first_name LIKE ? OR p.last_name LIKE ? OR mh.diagnosis LIKE ? OR mh.date LIKE ?)";
}

$stmt = $conn->prepare($sql);

if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("sssss", $specialist_id, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt->bind_param("s", $specialist_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Medical History</title>
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
    .content {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .content:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    h2 {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    h2:hover {
        letter-spacing: 1px;
        border-bottom-color: #2980b9;
    }
/* Search Form Styles */
form[method="get"] {
    display: flex;
    gap: 10px;
    margin: 20px 0;
    align-items: center;
}

form[method="get"] input[type="text"] {
    flex: 1;
    max-width: 500px;
    padding: 12px 15px;
    border: 2px solid #3498db;
    border-radius: 30px;
    font-size: 16px;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background-color: #f8f9fa;
}

form[method="get"] input[type="text"]:focus {
    border-color: #2980b9;
    box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
    background-color: #fff;
}

form[method="get"] input[type="text"]:hover {
    border-color: #2980b9;
}

form[method="get"] button {
    padding: 12px 20px;
    background:#7a6b8e;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 8px;
}

form[method="get"] button:hover {
    background:#4a6583;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

form[method="get"] button:active {
    transform: translateY(0);
    box-shadow: 0 2px 3px rgba(0,0,0,0.1);
}

/* Search icon inside button */
form[method="get"] button::before {
    content: "🔍";
    font-size: 16px;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    form[method="get"] {
        flex-direction: column;
        align-items: stretch;
    }
    
    form[method="get"] input[type="text"] {
        max-width: 100%;
    }
    
    form[method="get"] button {
        width: 100%;
        justify-content: center;
    }
}
    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    table:hover {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    th {
        background:linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
        color: white;
        padding: 12px;
        text-align: left;
        transition: all 0.3s ease;
    }

    th:hover {
        background:linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    tr:hover td {
        background-color: #f0f8ff;
        transform: scale(1.01);
    }

    /* Form Elements */
    input, select, button {
        transition: all 0.3s ease !important;
    }

    input:hover, select:hover {
        border-color: #3498db !important;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.3) !important;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body style="background: #f2f2f2;">
    <header>
        <h1>📂 View Medical History</h1>
        <nav>
            <a href="specialist_dashboard.php">🏠 Dashboard</a>
            <a href="view_patients.php">👨‍⚕️ View Patients</a>
            <a href="update_medical_history.php">✏️ Update Medical History</a>
            <a href="generate_report.php">📊 Generate Report</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <section class="content">
        <h2>Medical History Records</h2>

        <form method="get" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Search by name, date, diagnosis..." value="<?= htmlspecialchars($search) ?>" />
            <button type="submit"> Search</button>
        </form>

        <table border="1" cellpadding="10">
            <tr>
                <th>Patient Name</th>
                <th>Diagnosis</th>
                <th>Treatment</th>
                <th>Date</th>
                <th>Prescription</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($row['treatment']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['prescription']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>
</body>
</html>

<?php
$conn->close();
?>
