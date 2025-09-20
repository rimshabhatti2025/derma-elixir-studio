<?php
// Connect to database
include('db_connection.php');

// Fetch appointments from the database
$sql = "SELECT a.*, p.first_name AS patient_name, s.first_name AS specialist_name 
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN specialists s ON a.specialist_id = s.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Appointments</title>
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --success-color: #4bb543;
      --warning-color: #ffcc00;
      --danger-color: #f44336;
      --gray-color: #6c757d;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
    
    section {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 2rem;
    }
    
    h2 {
      color: #3a4b6d;
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
      position: relative;
      padding-bottom: 0.5rem;
    }
    
    h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background-color: var(--accent-color);
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
      overflow: hidden;
    }
    
    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #e0e0e0;
    }
    
    th {
      background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
    }
    
    tr:nth-child(even) {
      background-color: #f8f9fa;
    }
    
    tr:hover {
      background-color: #f1f3ff;
      transform: scale(1.01);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: all 0.2s ease;
    }
    
    td a {
      color: var(--accent-color);
      text-decoration: none;
      margin: 0 0.5rem;
      padding: 0.3rem 0.8rem;
      border-radius: 4px;
      transition: all 0.3s ease;
    }
    
    td a:first-child {
      background-color: rgba(72, 149, 239, 0.1);
      color: var(--accent-color);
    }
    
    td a:last-child {
      background-color: rgba(244, 67, 54, 0.1);
      color: var(--danger-color);
    }
    
    td a:hover {
      color: white;
    }
    
    td a:first-child:hover {
      background-color: var(--success-color);
    }
    
    td a:last-child:hover {
      background-color: var(--danger-color);
    }
    
    .status-badge {
      display: inline-block;
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
    }
    
    @media (max-width: 768px) {
      nav {
        flex-direction: column;
        gap: 0.5rem;
      }
      
      nav a.logout {
        margin-left: 0;
      }
      
      table {
        display: block;
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1><a href="#" style="color:white!important;text-decoration:none">📅 Manage Appointments</a></h1>
    <nav>
      <a href="admin_dashboard.php">🏠 Home</a>
      <a href="add_specialist.php">➕ Add Specialist</a>
      <a href="verify_certificates.php">📜 Verify Certificates</a>
      <a href="system_settings.php">⚙️ System Settings</a>
      <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
  </header>

  <section>
    <h2>Manage Appointments</h2>
    <table>
      <thead>
        <tr>
          <th>Appointment ID</th>
          <th>Patient Name</th>
          <th>Specialist Name</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['patient_name'] . "</td>";
            echo "<td>" . $row['specialist_name'] . "</td>";
            echo "<td>" . $row['appointment_date'] . "</td>";
            $status = $row['status'];
            $badge = '';

            if ($status === 'Pending') {
                $badge = "<span class='status-badge' style='background-color: rgba(255, 193, 7, 0.2); color: #ffa000;'>⏳ Pending</span>";
            } elseif ($status === 'Confirmed') {
                $badge = "<span class='status-badge' style='background-color: rgba(76, 181, 67, 0.2); color: var(--success-color);'>✅ Confirmed</span>";
            } elseif ($status === 'Cancelled') {
                $badge = "<span class='status-badge' style='background-color: rgba(244, 67, 54, 0.2); color: var(--danger-color);'>❌ Cancelled</span>";
            } else {
                $badge = "<span class='status-badge'>$status</span>"; // fallback
            }

            echo "<td>$badge</td>";
            echo "<td>
                    <a href='approve_appointment.php?id=" . $row['id'] . "'>Approve</a>
                    <a href='cancel_appointments.php?id=" . $row['id'] . "'>Cancel</a>
                  </td>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
  </section>
</body>
</html>