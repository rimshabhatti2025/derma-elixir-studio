<?php
include 'db_connection.php';

// Delete specialist
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM specialists WHERE id = $id");
    header("Location: specialist_list.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
if (!empty($search)) {
    $result = mysqli_query($conn, "SELECT * FROM specialists WHERE CONCAT(first_name, ' ', last_name) LIKE '%$search%'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM specialists");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Specialists</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333;
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
            color: #4a4a4a;
            margin: 2rem;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Table Styles */
        table {
            width: 100%;
            max-width: 1600px;
            margin: 2rem auto;
            border-collapse: collapse;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:nth-child(odd) {
            background-color: white;
        }

        tr:hover {
            background-color: #f1f3ff;
            transform: scale(1.01);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        /* Action Link Styles */
        td a {
            color: #ff4757;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: inline-block;
            background-color:  #fecaca;
        }

        td a:hover {
            background-color: #ff4757;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 71, 87, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }

            nav {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            table {
                width: 95%;
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 0.75rem;
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

    <h1>👨‍⚕️ Manage Specialists</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Qualification</th>
            <th>Expertise</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['first_name'] . ' ' . $row['last_name']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['mobile']; ?></td>
                <td><?= $row['qualification']; ?></td>
                <td><?= $row['area_of_expertise']; ?></td>
                <td>
                    <a href="?delete_id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>