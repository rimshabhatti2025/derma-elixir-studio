<?php
include("db_connection.php"); // or whatever your DB file is called

// Get all patients who uploaded a certificate and are not verified yet
$sql = "SELECT * FROM patients WHERE certificate_verified = 0 AND certificate_path IS NOT NULL";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Verify Certificates</title>
    <style>
        /* Base Styles */
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --success: #10b981;
            --success-dark: #059669;
            --danger: #ef4444;
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #f9fafb;
            --card-bg: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            transition: all 0.3s ease;
        }

        header {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
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

        /* Main Content Styles */
        h2 {
            color: var(--text);
            margin: 2.5rem auto 1.5rem;
            font-size: 1.75rem;
            font-weight: 700;
            text-align: center;
            max-width: 1200px;
            padding: 0 2rem;
            position: relative;
        }

        h2::after {
            content: "";
            position: absolute;
            bottom: -0.75rem;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            border-radius: 2px;
        }

        /* Table Styles */
        table {
            width: calc(100% - 4rem);
            max-width: 1200px;
            margin: 2rem auto;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--card-bg);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        th {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 1.25rem 1.5rem;
            position: sticky;
            top: 0;
        }

        td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        /* Certificate Link */
        td a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            background-color: #eef2ff;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        td a:hover {
            background-color: #e0e7ff;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
        }

        /* Verify Button */
        button[type="submit"] {
            background-color: var(--success);
            color: white;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        button[type="submit"]:hover {
            background-color: var(--success-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
                text-align: center;
            }

            nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.75rem;
            }

            h2 {
                font-size: 1.5rem;
                margin: 1.5rem auto 1rem;
                padding: 0 1rem;
            }

            table {
                width: calc(100% - 2rem);
                margin: 1.5rem auto;
                display: block;
                overflow-x: auto;
            }

            th,
            td {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1><a href="#" style="color:white!important;text-decoration:none">📜 Verify Certificates</a></h1>
        <nav>
            <a href="admin_dashboard.php">🏠 Home</a>
            <a href="add_specialist.php">➕ Add Specialist</a>
            <a href="appointments.php">📅 Manage Appointments</a>
            <a href="system_settings.php">⚙️ System Settings</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>
    <h2>Patients' Certificates Pending Verification</h2>
    <table>
        <tr>
            <th>Patient Name</th>
            <th>Certificate</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>

                <td>
                    <?php
                    $path = "../" . $row['certificate_path'];
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                    if ($ext === 'pdf') {
                        echo "<a href='$path' target='_blank'>📄 View PDF</a>";
                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        echo "<a href='$path' target='_blank'><img src='$path' alt='Certificate Image' width='100' style='border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);'></a>";
                    } else {
                        echo "❌ Unsupported format";
                    }
                    ?>
                </td>


                <td>
                    <form method="post" action="verify_certificate_action.php">
                        <input type="hidden" name="patient_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="verify">✅ Verify</button>
                    </form>
                </td>
            </tr>

        <?php } ?>
    </table>
</body>

</html>