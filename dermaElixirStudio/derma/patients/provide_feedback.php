<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $specialist_id = $_POST['specialist_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $image_path = null;

    // Handle image upload if a file was provided
    if (!empty($_FILES["feedback_image"]["name"])) {
        $target_dir = "feedback_images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // create directory if not exists
        }
    
        $image_ext = strtolower(pathinfo($_FILES["feedback_image"]["name"], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
    
        if (in_array($image_ext, $allowed_exts)) {
            $new_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $_FILES["feedback_image"]["name"]);
            $target_file = $target_dir . $new_name;
    
            if (move_uploaded_file($_FILES["feedback_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $message = "❌ Image upload failed.";
            }
        } else {
            $message = "❌ Only JPG, JPEG, PNG or WEBP images are allowed.";
        }
    }
    

    // Insert into reviews table
    $sql = "INSERT INTO reviews (patient_id, specialist_id, rating, feedback, image_path) 
            VALUES ('$patient_id', '$specialist_id', '$rating', '$feedback', " . 
            ($image_path ? "'$image_path'" : "NULL") . ")";

    if (mysqli_query($conn, $sql)) {
        $message = "Feedback submitted successfully!";
        header("Location: provide_feedback.php");
        exit();
    } else {
        $message = "Error submitting feedback: " . mysqli_error($conn);
    }
}

// Load specialists for the dropdown
$specialistQuery = "SELECT id, first_name, last_name FROM specialists";
$specialists = mysqli_query($conn, $specialistQuery);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Feedback</title>
    <link rel="stylesheet" href="provide_feedback.css">
    <style>
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
/* Feedback Form Styling (matching book appointment form) */
.feedback {
    max-width: 600px;
    width: 90%;
    margin: 3rem auto;
    background: white;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feedback:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

h1 {
    text-align: center;
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

/* Form Elements */
.form-group {
    margin-bottom: 1.8rem;
    position: relative;
}

label {
    display: block;
    margin-bottom: 0.6rem;
    font-weight: 600;
    color: #2c3e50;
    font-size: 1rem;
    transition: all 0.3s ease;
}

select, textarea {
    width: 100%;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    background: #f9f9f9;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

select {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%232c3e50" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 0.8rem center;
    background-size: 12px;
    cursor: pointer;
}

select:hover, textarea:hover {
    border-color: #b3d7ff;
    background: #f5f9ff;
}

select:focus, textarea:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    outline: none;
    background: #fff;
}

textarea {
    resize: vertical;
    min-height: 120px;
}

/* Rating Stars */
select[name="rating"] option {
    font-size: 1.2rem;
    padding: 0.5rem;
}

/* Submit Button */
button {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

button:hover {
    background: linear-gradient(135deg, #5a7695 0%, #9a7a93 100%);
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
}

button:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
}

/* Message */
.message {
    text-align: center;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    font-weight: 500;
}

.success {
    background-color: #d4edda;
    color: #155724;
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
        margin-right: 0;
    }
    
    .feedback {
        padding: 1.8rem;
    }
    
    h1 {
        font-size: 1.8rem;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.feedback {
    animation: fadeIn 0.6s ease-out forwards;
}
    </style>
</head>
<body>
    <header>
        <h1 style="color:white">📝 Provide Feedback</h1>
        <nav>
            <a href="patient_dashboard.php">🏠 Home</a>
            <a href="book_appointment.php">📅 Book Appointment</a>
            <a href="view_appointments.php">📂 View Appointments</a>
            <a href="medical_history.php">🩺 Medical History</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <section class="feedback">
        <center><h1>𝐒𝐮𝐛𝐦𝐢𝐭 𝐘𝐨𝐮𝐫 𝐅𝐞𝐞𝐝𝐛𝐚𝐜𝐤</h1></center>
        <?php if ($message): ?>
            <p style="color: green;"><?= $message ?></p>
        <?php endif; ?>

        <!-- enctype added for file uploads -->
        <form action="provide_feedback.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="specialist_id">Choose Specialist:</label>
                <select name="specialist_id" required>
                    <option value="">-- Select Specialist --</option>
                    <?php while ($row = mysqli_fetch_assoc($specialists)): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['first_name'] . " " . $row['last_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="rating">Your Rating:</label>
                <select name="rating" required>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>
            </div>

            <div class="form-group">
                <label for="feedback">Your Review:</label>
                <textarea name="feedback" rows="4" required></textarea>
            </div>

            <!-- NEW: Upload Image -->
            <div class="form-group">
                <label for="feedback_image">Upload Image (Optional):</label>
                <input type="file" name="feedback_image" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <button type="submit">Submit Feedback</button>
        </form>
    </section>
</body>

</html>
