<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

require 'vendor/autoload.php'; // Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;
include 'db_connection.php';

$patient_id = $_SESSION['patient_id'];

// Get patient name
$patient_query = $conn->prepare("SELECT first_name, last_name FROM patients WHERE id = ?");
$patient_query->bind_param("i", $patient_id);
$patient_query->execute();
$patient_result = $patient_query->get_result();
$patient = $patient_result->fetch_assoc();
$patient_name = $patient['first_name'] . ' ' . $patient['last_name'];

// Fetch reports
$sql = "SELECT r.*, s.first_name AS doc_fname, s.last_name AS doc_lname
        FROM reports r
        JOIN specialists s ON r.specialist_id = s.id
        WHERE r.patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

// Create styled HTML
$html = '
<style>
    body {
        font-family: "Helvetica Neue", Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #8e6c88;
        padding-bottom: 20px;
    }
    .header h1 {
        color: #2a3a57;
        margin-bottom: 5px;
    }
    .header h2 {
        color: #8e6c88;
        margin-top: 0;
    }
    .patient-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .report-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .report-item:last-child {
        border-bottom: none;
    }
    .label {
        font-weight: bold;
        color: #2a3a57;
        display: inline-block;
        width: 180px;
    }
    .footer {
        text-align: center;
        margin-top: 40px;
        color: #666;
        font-size: 14px;
    }
    .clinic-name {
        font-weight: bold;
        color: #8e6c88;
    }
    .watermark {
        position: fixed;
        bottom: 50%;
        left: 50%;
        transform: translate(-50%, 50%) rotate(-15deg);
        font-size: 4em;
        color: rgba(46, 108, 173, 0.1);
        z-index: -1;
        pointer-events: none;
        user-select: none;
    }
</style>

<div class="watermark">Derma Elixir Studio</div>

<div class="header">
    <h1>Derma Elixir Studio</h1>
    <h2>Patient Medical Report</h2>
</div>

<div class="patient-info">
    <h3>Patient: '.$patient_name.'</h3>
</div>
';

while ($row = $result->fetch_assoc()) {
    $doctor_name = $row['doc_fname'] . ' ' . $row['doc_lname'];
    
    $html .= '
    <div class="report-item">
        <p><span class="label">Date:</span> '.$row['report_date'].'</p>
        <p><span class="label">Doctor:</span> '.$doctor_name.'</p>
        <p><span class="label">Report Details:</span> '.$row['report_details'].'</p>
        <p><span class="label">Prescribed Medication:</span> '.$row['prescribed_medication'].'</p>
        <p><span class="label">Lab Tests:</span> '.$row['lab_tests'].'</p>
        <p><span class="label">Treatment:</span> '.$row['treatment'].'</p>
    </div>
    ';
}

$html .= '
<div class="footer">
    <p class="clinic-name">Derma Elixir Studio</p>
    <p>123 Clinic Street, Islamabad | Phone: (123) 456-7890</p>
    <p>Report generated on: '.date('Y-m-d').'</p>
</div>
';

// Close the while loop properly


// If download button clicked, render as PDF
if (isset($_GET['download']) && $_GET['download'] == 'pdf') {
    $options = new Options();
    $options->set('defaultFont', 'Helvetica');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("patient_report.pdf", ["Attachment" => true]);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Report</title>
    <style>
    /* Base Styles */
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


    /* Header Styles */
    h1, h2, h3, h4 {
        color: #2c3e50;
        font-weight: 600;
    }

    h2 {
        color: #3498db;
        margin-bottom: 5px;
        font-size: 28px;
    }

    h4 {
        color: #16a085;
        margin-top: 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }

    /* Report Container */
    .report-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    /* Report Boxes */
    .report-box {
        border-left: 4px solid #3498db;
        background: #f8fbfe;
        padding: 20px;
        margin-bottom: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }

    .report-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Info Elements */
    .info {
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #ecf0f1;
    }

    .info:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .label {
        font-weight: 600;
        color: #2980b9;
        display: inline-block;
        min-width: 180px;
    }

    /* Title Styles */
    .title {
        font-size: 24px;
        color: #2c3e50;
        text-align: center;
        margin: 20px 0 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
        position: relative;
    }

    .title:after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, #3498db, #9b59b6);
    }

    /* Watermark */
    .watermark {
        position: fixed;
        bottom: 50%;
        left: 50%;
        transform: translate(-50%, 50%) rotate(-15deg);
        font-size: 4em;
        color: rgba(52, 152, 219, 0.1);
        z-index: -1;
        text-align: center;
        font-weight: bold;
        pointer-events: none;
        user-select: none;
    }

    /* Footer */
    footer {
        margin-top: 40px;
        padding-top: 20px;
        text-align: center;
        color: #7f8c8d;
        font-size: 14px;
        border-top: 1px solid #ecf0f1;
    }

    .clinic-name {
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .address {
        font-style: italic;
    }

    /* Logo */
    .logo {
        position: absolute;
        top: 20px;
        left: 20px;
        height: 60px;
        opacity: 0.9;
    }

    /* Download Button */
    .download-btn {
        display: inline-block;
        padding: 15px 50px;
        background: linear-gradient(135deg, #3498db, #2ecc71);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #2980b9, #27ae60);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .report-container {
            padding: 20px;
        }
        
        .label {
            display: block;
            margin-bottom: 5px;
        }
        
        .logo {
            position: relative;
            top: auto;
            left: auto;
            display: block;
            margin: 0 auto 20px;
        }
    }

    /* Print/PDF Specific Styles */
    @media print {
        body {
            background: none;
            padding: 0;
        }
        
        .report-container {
            box-shadow: none;
            max-width: 100%;
            padding: 0;
        }
        
        .download-btn {
            display: none;
        }
        
        .watermark {
            opacity: 0.2;
        }
    }
</style>
</head>
<body style="padding-top: 70px;">
<!-- Add this right after the <body> tag -->
<header style="position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    justify-content: space-between; /* Keep this */
    align-items: center;
    padding: 1.5rem 2.5rem; /* Reduced padding */
    background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    margin: 0;
    left: 0;
    right: 0;
    box-sizing: border-box;"> <!-- Critical addition -->

    <!-- Title/Logo Section -->
    <div style="flex: 0 0 auto; min-width: 120px;"> 
        <h1 style="margin: 0; font-size: 2rem;">
            <a href="#" style="color: white; text-decoration:none;">📄 Reports</a>
        </h1>
    </div>

    <!-- Navigation Section -->
    <nav style="display: flex; 
                gap: 0.5rem; 
                padding: 0;
                margin: 0;
                flex-wrap: wrap;
                justify-content: flex-end;
                flex: 1 1 auto;">
                
        <a href="patient_dashboard.php" style="color: white; 
                   text-decoration: none; 
                   padding: 0.25rem 0.5rem;
                   font-size: 1rem;
                   white-space: nowrap;">🏠 Home</a>
                   
        <!-- Repeat same style for other links -->
        <a href="book_appointment.php" style="color: white; 
                   text-decoration: none; 
                   padding: 0.5rem 1rem;
                   font-size: 1rem;
                   white-space: nowrap;">📅 Book Appointments</a>
        <a href="view_appointments.php" style="color: white; 
                   text-decoration: none; 
                   padding: 0.5rem 1rem;
                   font-size: 1rem;
                   white-space: nowrap;">📂 View Appointments</a>
        <a href="medical_history.php" style="color: white; 
                   text-decoration: none; 
                   padding: 0.5rem 1rem;
                   font-size: 1rem;
                   white-space: nowrap;">🩺 Medical History</a>
        <a href="provide_feedback.php" style="color: white; 
                   text-decoration: none; 
                   padding: 0.5rem 1rem;
                   font-size: 1rem;
                   white-space: nowrap;">📝 Provide Feedback</a>
        
        <a href="../logout.php" 
           style="color: white; 
                  text-decoration: none; 
                  padding: 0.5rem 1rem;
                  background: rgba(255,0,0,0.7); 
                  border-radius: 4px;
                  font-size: 1rem;
                  white-space: nowrap;">🚪 Logout</a>
    </nav>
</header>

<!-- Add this right after the header -->
<div style="padding-top: 80px;">  <!-- This pushes content below fixed header -->
    <!-- Your page content goes here -->
    <center><h1>🩺 Patient Reports</h1></center>
    <hr>
    <?= $html ?>
    <center><a href="download_report.php?download=pdf" class="download-btn">⬇️ Download PDF</a></center>
    <footer>
        <div class="clinic-name">Derma Elixir Studio</div>
        <div class="address">123 Clinic Street, Islamabad</div>
    </footer>

</body>
</html>
