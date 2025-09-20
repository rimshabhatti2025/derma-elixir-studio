<?php
include("db_connection.php");

// Fetch all system settings
$sql = "SELECT * FROM system_settings";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>System Settings</title>
    <style>
        /* General Styles */
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
            color: #4a5568;
            margin: 2.5rem 0 1.5rem;
            font-size: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        h2:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #5a67d8, #805ad5);
            border-radius: 3px;
        }

        /* Form and Table Styles */
        form {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            overflow: hidden;
            background: white;
            margin: 2rem 0;
        }

        th,
        td {
            padding: 1.2rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
        }

        th {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Input Styles */
        input[type="text"] {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #5a67d8;
            box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.2);
        }

        input[type="text"]:hover {
            border-color: #a3bffa;
        }

        /* Button Styles */
        button {
            background: #6a4a6e;
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            background: #3a4b6d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
                text-align: center;
            }

            nav {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.8rem;
            }

            table {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 0.8rem;
            }

            h2 {
                font-size: 1.6rem;
                margin: 1.5rem 0 1rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1><a href="#" style="color:white!important;text-decoration:none">⚙️ System Settings</a></h1>
        <nav>
            <a href="admin_dashboard.php">🏠 Home</a>
            <a href="add_specialist.php">➕ Add Specialist</a>
            <a href="appointments.php">📅 Manage Appointments</a>
            <a href="verify_certificates.php">📜 Verify Certificates</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>
    <h2 style="text-align:center;">System Settings</h2>
    <form action="update_settings.php" method="post">
        <table>
            <tr>
                <th>Setting Name</th>
                <th>Value</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['setting_name']); ?></td>
                    <td>
                        <input type="text" name="settings[<?php echo $row['id']; ?>]" value="<?php echo htmlspecialchars($row['setting_value']); ?>">
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div style="text-align:center; margin-top:20px;">
            <button type="submit">💾 Save Changes</button>
        </div>
    </form>
</body>

</html>