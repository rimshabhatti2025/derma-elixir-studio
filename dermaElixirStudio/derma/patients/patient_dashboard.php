<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
  header("Location: login.php");
  exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch specialist info including photo
$sql = "SELECT first_name, last_name, profile_photo_path FROM patients WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// ✅ Set profile photo path
$profilePath = $patient['profile_photo_path'] ?? '';
$fullPath = "../" . $profilePath;
$profileSrc = (!empty($profilePath) && file_exists($fullPath))
  ? htmlspecialchars($fullPath)
  : '../profile_photos/default_avatar.png';

// Fetch appointment count
$countQuery = "SELECT COUNT(*) AS total FROM appointments WHERE patient_id = '$patient_id'";
$countResult = mysqli_query($conn, $countQuery);
$totalAppointments = mysqli_fetch_assoc($countResult)['total'] ?? 0;

// Fetch upcoming appointments
$sql = "SELECT a.id, s.first_name, s.last_name, a.appointment_date, a.appointment_time, a.status 
        FROM appointments a
        JOIN specialists s ON a.specialist_id = s.id
        WHERE a.patient_id = '$patient_id'
        ORDER BY a.appointment_date ASC, a.appointment_time ASC";
$result = mysqli_query($conn, $sql);

// Fetch the next upcoming appointment
$nextAppointmentQuery = "SELECT s.first_name, s.last_name, a.appointment_date, a.appointment_time 
                         FROM appointments a 
                         JOIN specialists s ON a.specialist_id = s.id 
                         WHERE a.patient_id = '$patient_id' AND a.appointment_date >= CURDATE() 
                         ORDER BY a.appointment_date ASC, a.appointment_time ASC LIMIT 1";
$nextAppointmentResult = mysqli_query($conn, $nextAppointmentQuery);
$nextAppointment = mysqli_fetch_assoc($nextAppointmentResult);

// Fetch last feedback given by the patient
$feedbackQuery = "SELECT rating, feedback FROM reviews WHERE patient_id = '$patient_id' ORDER BY created_at DESC LIMIT 1";
$feedbackResult = mysqli_query($conn, $feedbackQuery);
$lastFeedback = mysqli_fetch_assoc($feedbackResult);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Dashboard</title>
  <link rel="stylesheet" href="patientstyles.css">
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
      background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
      color: #fff;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      line-height: 30px;
      margin-bottom: 70px;
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

    /* Welcome Section */
    .patient-welcome {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
      animation: fadeIn 1s ease;
      margin-bottom: 70px;
    }

    .welcome-content {
      display: flex;
      align-items: center;
      gap: 40px;
    }

    .welcome-content img {
      width: 250px;
      height: 250px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    /* Updated Dashboard Summary Cards */
    .dashboard-summary {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1.5rem;
      margin: 2rem auto;
      padding: 0 1rem;
      max-width: 1200px;
    }

    .top-row {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      width: 100%;
      flex-wrap: wrap;
    }

    .bottom-row {
      display: flex;
      justify-content: center;
      width: 100%;
    }

    .stat-box {
      background: white;
      padding: 1.8rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      text-align: center;
      width: 100%;
      max-width: 400px;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Dashboard Summary Cards */
    .dashboard-summary {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin: 2rem auto;
      flex-wrap: wrap;
      padding: 0 1rem;
    }

    .stat-box {
      background: white;
      padding: 1.8rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      text-align: center;
      width: 300px;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
      border-color: rgba(52, 152, 219, 0.3);
    }

    .stat-box h3 {
      margin-bottom: 1rem;
      color: #2c3e50;
      font-size: 1.2rem;
      font-weight: 600;
      position: relative;
      padding-bottom: 0.8rem;
    }

    .stat-box h3::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: #3498db;
      border-radius: 3px;
    }

    .stat-box p {
      margin: 0.8rem 0;
      color: #555;
      font-size: 1rem;
      transition: color 0.3s ease;
    }

    .stat-box:hover p {
      color: #333;
    }

    /* Appointments Section */
    .appointments {
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      margin: 2rem auto;
      max-width: 95%;
    }

    .appointments h2 {
      text-align: center;
      color: #2c3e50;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }

    /* Table Styles */
    table {
      background: white;
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      background-color: white;
    }

    table th,
    table td {
      padding: 1rem;
      text-align: center;
      border-bottom: 1px solid #eee;
      transition: all 0.2s ease;
      background-color: white;
    }

    table th {
      background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
      color: #fff;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
    }

    table tr:not(:first-child):hover {
      background-color: #f8fafc;
      transform: scale(1.005);
    }

    table tr:active {
      background-color: #f1f5f9;
    }

    /* Status Badges */
    .status {
      font-weight: 600;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      display: inline-block;
      min-width: 90px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .status.pending {
      background-color: #FFF3CD;
      color: #856404;
    }

    .status.approved {
      background-color: #D4EDDA;
      color: #155724;
    }

    .status.canceled {
      background-color: #F8D7DA;
      color: #721C24;
    }

    /* Buttons */
    .cancel-btn {
      background: #e74c3c;
      color: white;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cancel-btn:hover {
      background: #c0392b;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .cancel-btn:active {
      transform: translateY(0);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
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

      .dashboard-summary {
        flex-direction: column;
        align-items: center;
      }

      .stat-box {
        width: 100%;
        max-width: 350px;
      }

      table {
        display: block;
        overflow-x: auto;
      }
    }

    /* Animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .stat-box,
    .appointments {
      animation: fadeIn 0.5s ease-out forwards;
    }

    .appointments {
      animation-delay: 0.1s;
    }
  </style>
</head>

<body style="background: linear-gradient(135deg, rgba(168, 237, 236, 0.6), rgba(254, 214, 227, 0.79));">
  <header>
    <h1><a href="#" style="color: white; text-decoration:none;">👩‍⚕️ Patient Dashboard</a></h1>
    <nav>
      <a href="book_appointment.php">📅 Book Appointment</a>
      <a href="medical_history.php">🩺 Medical History</a>
      <a href="download_report.php">📄 Download Report</a> <!-- New -->
      <a href="search.php">🔍 Search</a> <!-- New -->
      <a href="provide_feedback.php">📝 Provide Feedback</a>
      <a href="settings.php">⚙️ Settings</a>
      <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
  </header>

  <br>
  <div class="patient-welcome">
    <div class="welcome-content">
      <img src="<?= $profileSrc ?>" alt="Profile Photo">
      <div>
        <h1>Welcome, <?= $patient['first_name'] . " " . $patient['last_name'] ?> 👋</h1>
        <p>Select an action from the navigation menu above.</p>
      </div>
    </div>
  </div>



  <section class="dashboard-summary">
    <div class="top-row">
      <div class="stat-box">
        <h3>Total Appointments</h3>
        <p><?= $totalAppointments ?></p>
      </div>
      <div class="stat-box">
        <h3>Next Appointment</h3>
        <?php if ($nextAppointment): ?>
          <p>👨‍⚕️ <?= $nextAppointment['first_name'] . " " . $nextAppointment['last_name'] ?></p>
          <p>📅 <?= $nextAppointment['appointment_date'] ?> at ⏰ <?= $nextAppointment['appointment_time'] ?></p>
        <?php else: ?>
          <p>No upcoming appointments</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="bottom-row">
      <div class="stat-box">
        <h3>Last Feedback</h3>
        <?php if ($lastFeedback): ?>
          <p>⭐ Rating: <?= str_repeat("⭐", $lastFeedback['rating']) ?></p>
          <p>"<?= $lastFeedback['feedback'] ?>"</p>
        <?php else: ?>
          <p>No feedback given yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <section class="appointments">
    <h2>Your Appointments</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Specialist</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td>👨‍⚕️ <?= $row['first_name'] . " " . $row['last_name'] ?></td>
              <td>📅 <?= $row['appointment_date'] ?></td>
              <td>⏰ <?= $row['appointment_time'] ?></td>
              <td><span class="status <?= strtolower($row['status']) ?>">📌 <?= $row['status'] ?></span></td>
              <td>
                <a href="cancel_appointment.php?id=<?= $row['id'] ?>" class="cancel-btn" onclick="return confirmDelete()">❌ Cancel</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">No appointments found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>

  <script>
    function confirmDelete() {
      return confirm("Are you sure you want to cancel this appointment?");
    }
  </script>

</body>

</html>