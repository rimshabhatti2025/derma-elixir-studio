<?php
// Include database connection file
include('db_connection.php');

// Fetch total number of doctors (specialists)
$query_doctors = "SELECT COUNT(*) AS total_doctors FROM specialists";
$result_doctors = mysqli_query($conn, $query_doctors);
$row_doctors = mysqli_fetch_assoc($result_doctors);
$total_doctors = $row_doctors['total_doctors'];

// Fetch total number of patients
$query_patients = "SELECT COUNT(*) AS total_patients FROM patients";
$result_patients = mysqli_query($conn, $query_patients);
$row_patients = mysqli_fetch_assoc($result_patients);
$total_patients = $row_patients['total_patients'];

// Fetch total number of appointments
$query_appointments = "SELECT COUNT(*) AS total_appointments FROM appointments";
$result_appointments = mysqli_query($conn, $query_appointments);
$row_appointments = mysqli_fetch_assoc($result_appointments);
$total_appointments = $row_appointments['total_appointments'];

// Fetch recent appointments
$query_recent_appointments = "SELECT a.*, 
                            CONCAT(p.first_name, ' ', p.last_name) AS patient_name,
                            CONCAT(s.first_name, ' ', s.last_name) AS specialist_name
                            FROM appointments a
                            JOIN patients p ON a.patient_id = p.id
                            JOIN specialists s ON a.specialist_id = s.id
                            ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT 5";
$result_recent_appointments = mysqli_query($conn, $query_recent_appointments);

// Fetch system status
$query_system_status = "SELECT 
                        (SELECT COUNT(*) FROM specialists) AS total_doctors,
                        (SELECT COUNT(*) FROM appointments WHERE status = 'Completed') AS completed_appointments,
                        (SELECT COUNT(*) FROM appointments WHERE status = 'Cancelled') AS cancelled_appointments,
                        (SELECT COUNT(*) FROM appointments WHERE status = 'Pending') AS pending_appointments,
                        (SELECT COUNT(*) FROM appointments WHERE status = 'Confirmed') AS confirmed_appointments";
$result_system_status = mysqli_query($conn, $query_system_status);
$system_status = mysqli_fetch_assoc($result_system_status);

// Fetch specialist specialties statistics
$query_specialties = "SELECT area_of_expertise, COUNT(*) as count 
                      FROM specialists 
                      GROUP BY area_of_expertise 
                      ORDER BY count DESC LIMIT 5";
$result_specialties = mysqli_query($conn, $query_specialties);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Derma Elixir Studio</title>
    <link rel="stylesheet" href="adminstyles.css">

</head>

<body style="background: linear-gradient(135deg, #dff6ff, #ffe4e1);">

    <header>
        <h1><a href="admin_dashboard.php" style="color:white!important;text-decoration:none">🧑‍💼 Admin Dashboard</a></h1>
        <nav>
            <a href="search.php">🔍 Search</a> <!-- New -->
            <a href="add_specialist.php">➕ Add Specialist</a>
            <a href="appointments.php">📅 Manage Appointments</a>
            <a href="verify_certificates.php">📜 Verify Certificates</a>
            <a href="system_settings.php">⚙️ System Settings</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <section class="dashboard-content">
        <h2>Welcome, Admin!</h2>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Specialists</h3>
                <p style="color: #6a4a6e"><?php echo $total_doctors; ?></p>
                <div class="stat-details">
                    <span>Top Specialty: <?php
                                            if ($specialty = mysqli_fetch_assoc($result_specialties)) {
                                                echo htmlspecialchars($specialty['area_of_expertise']);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?></span>
                </div>
            </div>

            <div class="stat-card">
                <h3>Total Patients</h3>
                <p style="color: #6a4a6e"><?php echo $total_patients; ?></p>
            </div>

            <div class="stat-card">
                <h3>Total Appointments</h3>
                <p style="color: #6a4a6e"><?php echo $total_appointments; ?></p>
                <div class="stat-details">
                    <span class="stat-confirmed"><?php echo $system_status['confirmed_appointments']; ?> Confirmed</span>
                    <span class="stat-pending"><?php echo $system_status['pending_appointments']; ?> Pending</span>
                </div>
            </div>

            <div class="stat-card">
                <h3>Appointment Status</h3>
                <div class="status-bars">
                    <div class="status-bar completed" style="width: <?php echo ($total_appointments > 0) ? ($system_status['completed_appointments'] / $total_appointments) * 100 : 0; ?>%">
                        <span>Completed: <?php echo $system_status['completed_appointments']; ?></span>
                    </div>
                    <div class="status-bar cancelled" style="width: <?php echo ($total_appointments > 0) ? ($system_status['cancelled_appointments'] / $total_appointments) * 100 : 0; ?>%">
                        <span>Cancelled: <?php echo $system_status['cancelled_appointments']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="dashboard-columns">
            <!-- Left Column -->
            <div class="dashboard-column">
                <div class="card">
                    <h3>Recent Appointments</h3>
                    <div class="appointments-list">
                        <?php while ($appointment = mysqli_fetch_assoc($result_recent_appointments)): ?>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong> with
                                    <strong><?php echo htmlspecialchars($appointment['specialist_name']); ?></strong>
                                </div>
                                <div class="appointment-meta">
                                    <span><?php echo date('M j, Y', strtotime($appointment['appointment_date'])); ?> at <?php echo htmlspecialchars($appointment['appointment_time']); ?></span>
                                    <span class="status-badge <?php echo strtolower($appointment['status']); ?>">
                                        <?php echo htmlspecialchars($appointment['status']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <a href="appointments.php" class="btn" style="background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%)">View All Appointments</a>
                </div>
            </div>

            <!-- Right Column -->
            <div class="dashboard-column">
                <div class="card">
                    <h3>Quick Actions</h3>
                    <div class="quick-actions">
                        <a href="add_specialist.php" class="quick-action">
                            <span class="action-icon">➕</span>
                            <span>Add New Specialist</span>
                        </a>
                        <a href="appointments.php" class="quick-action">
                            <span class="action-icon">📅</span>
                            <span>Manage Appointment</span>
                        </a>
                        <a href="manage_patients.php" class="quick-action">
                            <span class="action-icon">👥</span>
                            <span>Manage Patients</span>
                        </a>
                        <a href="verify_certificates.php" class="quick-action">
                            <span class="action-icon">📜</span>
                            <span>Verify Certificates</span>
                        </a>
                        <a href="add_treatments.php" class="quick-action">
                            <span class="action-icon">➕</span>
                            <span>Add New Treatments</span>
                        </a>
                    </div>
                </div>

                <div class="card">
                    <h3>Appointment Statistics</h3>
                    <div class="appointment-stats">
                        <div class="stat-item">
                            <div class="stat-label">Pending</div>
                            <div class="stat-value"><?php echo $system_status['pending_appointments']; ?></div>
                            <div class="stat-bar pending" style="width: <?php echo ($total_appointments > 0) ? ($system_status['pending_appointments'] / $total_appointments) * 100 : 0; ?>%"></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Confirmed</div>
                            <div class="stat-value"><?php echo $system_status['confirmed_appointments']; ?></div>
                            <div class="stat-bar confirmed" style="width: <?php echo ($total_appointments > 0) ? ($system_status['confirmed_appointments'] / $total_appointments) * 100 : 0; ?>%"></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Completed</div>
                            <div class="stat-value"><?php echo $system_status['completed_appointments']; ?></div>
                            <div class="stat-bar completed" style="width: <?php echo ($total_appointments > 0) ? ($system_status['completed_appointments'] / $total_appointments) * 100 : 0; ?>%"></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Cancelled</div>
                            <div class="stat-value"><?php echo $system_status['cancelled_appointments']; ?></div>
                            <div class="stat-bar cancelled" style="width: <?php echo ($total_appointments > 0) ? ($system_status['cancelled_appointments'] / $total_appointments) * 100 : 0; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr style="height: 2px; background-color: black; border: none;">

        <!-- Additional Management Cards -->
        <h3 class="section-title">Management Tools</h3>
        <div class="management-tools">
            <div class="card">
                <h3>Specialists Management</h3>
                <p>View, edit, or remove specialists from the system.</p>
                <a href="specialist_list.php" class="btn" style="background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%)">Manage Specialists</a>
            </div>

            <div class="card">
                <h3>Patient Management</h3>
                <p>View, edit, or remove patients from the system.</p>
                <a href="manage_patients.php" class="btn" style="background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%)">Manage Patients</a>
            </div>

            <div class="card">
                <h3>Appointment Management</h3>
                <p>Manage all appointments in the system.</p>
                <a href="appointments.php" class="btn" style="background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%)">Manage Appointments</a>
            </div>
        </div>
    </section>

    <footer style="background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);">
        <p>&copy; <?php echo date('Y'); ?> Derma Elixir Studio. All rights reserved.</p>
    </footer>

</body>

</html>