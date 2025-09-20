<?php
session_start();
if (!isset($_SESSION['specialist_id'])) {
    header("Location: ../login.php");
    exit();
}

require 'db_connection.php'; // Database connection

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Search for specialists
$specialist_query = $conn->prepare("SELECT * FROM specialists WHERE first_name LIKE ? OR last_name LIKE ? OR area_of_expertise LIKE ?");
$search_term = "%$search_query%";
$specialist_query->bind_param("sss", $search_term, $search_term, $search_term);
$specialist_query->execute();
$specialist_result = $specialist_query->get_result();

// Search for Patients
$patient_query = $conn->prepare("SELECT * FROM patients WHERE first_name LIKE ? OR last_name LIKE ?");
$search_term = "%$search_query%";
$patient_query->bind_param("ss", $search_term, $search_term);
$patient_query->execute();
$patient_result = $patient_query->get_result();

// Search for treatments
$treatment_query = $conn->prepare("SELECT * FROM treatments WHERE name LIKE ? OR category LIKE ? OR description LIKE ?");
$treatment_query->bind_param("sss", $search_term, $search_term, $search_term);
$treatment_query->execute();
$treatment_result = $treatment_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        /* Search Form Styles */
        form {
            display: flex;
            margin: 30px 0;
            gap: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.2);
        }

        button[type="submit"] {
            padding: 12px 25px;
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.2);
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(52, 152, 219, 0.3);
        }

        /* Results Section */
        h3 {
            color: #2c3e50;
            font-size: 24px;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
            position: relative;
        }

        h3::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #3498db, #9b59b6);
        }

        .result-list {
            list-style-type: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .result-list li {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #3498db;
        }

        .result-list li:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            border-left: 4px solid #9b59b6;
        }

        .result-list li strong {
            color: #2c3e50;
            font-size: 18px;
            display: block;
            margin-bottom: 5px;
        }

        .result-list li em {
            color: #7f8c8d;
            font-style: normal;
            display: block;
            margin-bottom: 10px;
        }

        .result-list li p {
            margin: 8px 0;
            color: #555;
        }

        .result-list li p strong {
            display: inline;
            color: #e74c3c;
            font-size: inherit;
        }

        /* No Results Message */
        p.no-results {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            color: #7f8c8d;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .result-list {
                grid-template-columns: 1fr;
            }

            form {
                flex-direction: column;
            }

            button[type="submit"] {
                width: 100%;
            }
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #4a6583 0%, #7a6b8e 100%);
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            line-height: 30px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            margin-right: 20px;
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

        /* Add padding to body to account for fixed header */
        body {
            padding-top: 70px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>🔍 Search</h1>
            <nav>
            <a href="specialist_dashboard.php">🏠 Dashboard</a>
            <a href="view_patients.php">👨‍⚕️ View Patients</a>
            <a href="view_medical_history.php">📂 View Medical History</a>
            <a href="update_medical_history.php">✏️ Update Medical History</a>
            <a href="generate_report.php">📊 Generate Report</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
            </nav>
        </header>

        <!-- Search Form -->
        <form action="search.php" method="GET">
            <input type="text" name="search" placeholder="Search for Specialists, Patients or Treatments" value="<?= htmlspecialchars($search_query) ?>" />
            <button type="submit">Search</button>
        </form>

        <hr>

        <!-- Specialists Results -->
        <?php if ($specialist_result->num_rows > 0) { ?>
            <h3>👨‍⚕️ Specialists</h3>
            <ul class="result-list">
                <?php while ($specialist = $specialist_result->fetch_assoc()) { ?>
                    <li>
                        <img src="../<?php echo htmlspecialchars($specialist['profile_photo_path']); ?>" alt="Profile Photo" style="width:200px; height:200px; object-fit:cover; border-radius:50%;">
                        <strong><?php echo $specialist['first_name'] . ' ' . $specialist['last_name']; ?></strong> -
                        <em><?php echo $specialist['area_of_expertise']; ?></em><br>
                    </li>

                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No specialists found matching your search.</p>
        <?php } ?>


        <!-- Patients Results -->
        <?php if ($patient_result->num_rows > 0) { ?>
            <h3>👨‍⚕️ Patients</h3>
            <ul class="result-list">
                <?php while ($patient = $patient_result->fetch_assoc()) { ?>
                    <li>
                        <img src="../<?php echo htmlspecialchars($patient['profile_photo_path']); ?>" alt="Profile Photo" style="width:200px; height:200px; object-fit:cover; border-radius:50%;">
                        <strong><?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?></strong> - <br>
                    </li>

                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No Patients found matching your search.</p>
        <?php } ?>


        <!-- Treatments Results -->
        <?php if ($treatment_result->num_rows > 0) { ?>
            <h3>💉 Treatments</h3>
            <ul class="result-list">
                <?php while ($treatment = $treatment_result->fetch_assoc()) { ?>
                    <li>
                        <strong><?php echo $treatment['name']; ?></strong> -
                        <em><?php echo $treatment['category']; ?></em><br>
                        <p><?php echo $treatment['description']; ?></p>
                        <p><strong>Price:</strong> <?php echo number_format($treatment['price'], 2); ?></p>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No treatments found matching your search.</p>
        <?php } ?>
    </div>
</body>

</html>