<?php
include 'db_connection.php';

// Delete patient
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM patients WHERE id = $id");
    header("Location: manage_patients.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
if (!empty($search)) {
    $result = mysqli_query($conn, "SELECT * FROM patients WHERE CONCAT(first_name, ' ', last_name) LIKE '%$search%'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM patients");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Patients</title>
    <style>
        /* Base Styles */
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --success-color: #10b981;
            --text-color: #374151;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.5;
        }

        header {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
            color: #fff;
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            margin-left: 30px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        nav {
            display: flex;
            gap: 15px;
            margin-right: 20px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .logout {
            background-color: rgba(255, 0, 0, 0.762);
            margin-right: 30px;
            padding: 0.6rem 1.5rem;
        }

        .logout:hover {
            background-color: rgba(255, 0, 0, 0.9);
            transform: translateY(-2px);
        }


        /* Main Content Styles */
        h1 {
            color: #111827;
            margin: 2rem 2.5rem;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Table Styles */
        table {
            width: calc(100% - 5rem);
            margin: 1rem 2.5rem 3rem;
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            overflow: hidden;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
        }

        td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        /* Status Badges */
        td:nth-child(7) {
            font-weight: 500;
        }

        td:nth-child(7):contains("Verified") {
            color: var(--success-color);
        }

        td:nth-child(7):contains("Not Verified") {
            color: var(--danger-color);
        }

        /* Action Links */
        td a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        td a[href*="certificate_path"] {
            color: var(--primary-color);
            background-color: #eef2ff;
        }

        td a[href*="certificate_path"]:hover {
            background-color: #e0e7ff;
            transform: translateY(-1px);
        }

        td a[onclick] {
            color: var(--danger-color);
            background-color: #fee2e2;
        }

        td a[onclick]:hover {
            background-color: #fecaca;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            nav {
                flex-wrap: wrap;
                justify-content: center;
            }

            h1,
            table {
                margin-left: 1rem;
                margin-right: 1rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1><a href="admin_dashboard.php" style="color:white!important;text-decoration:none">Admin Dashboard</a></h1>
        <nav>
            <a href="add_specialist.php">➕ Add Specialist</a>
            <a href="appointments.php">📅 Manage Appointments</a>
            <a href="verify_certificates.php">📜 Verify Certificates</a>
            <a href="system_settings.php">⚙️ System Settings</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <form method="GET" style="text-align:center; margin-top: 60px;">
        <input type="text" name="search" placeholder="Search by name..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding:15px; width: 500px; border-radius: 6px; border: 1px solid #ccc;">
        <button type="submit" style="padding: 15px 30px;margin:20px; background: #3a4b6d; color: white; border: none; border-radius: 6px; cursor: pointer;">Search</button>
    </form>


    <h1>👩‍⚕️ Manage Patients</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>CNIC</th>
            <th>City</th>
            <th>Certificate</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['first_name'] . ' ' . $row['last_name']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['mobile']; ?></td>
                <td><?= $row['cnic']; ?></td>
                <td><?= $row['city']; ?></td>
                <td><?= $row['certificate_verified'] ? "✔️ Verified" : "❌ Not Verified"; ?></td>
                <td>
                    <a href="?delete_id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>