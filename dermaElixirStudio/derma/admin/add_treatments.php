<?php
session_start();
include '../db_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize the form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Insert the new treatment into the database
    $query = "INSERT INTO treatments (name, category, description, price) 
              VALUES ('$name', '$category', '$description', '$price')";

    if (mysqli_query($conn, $query)) {
        $message = "Treatment added successfully.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Treatment - Admin</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Arial", sans-serif;
        }

        /* Body Styling */
        body {
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 0;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #3a4b6d 0%, #6a4a6e 100%);
            color: #fff;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        header h1 {
            margin-left: 30px;
            transition: transform 0.3s ease;
        }

        header h1 a {
            color: white !important;
            text-decoration: none;
        }

        header h1:hover {
            transform: translateX(5px);
        }

        nav {
            display: flex;
            gap: 1rem;
            margin-right: 30px;
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
            content: "";
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
            transition: all 0.3s ease;
        }

        .logout:hover {
            background-color: rgba(255, 0, 0, 0.544);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
        }


        /* Form Container Styling */
        form {
            background-color: white;
            padding: 30px;
            margin-top: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeIn 1s ease-out;
        }

        /* Form Elements Styling */
        form label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }

        form input:focus,
        form select:focus,
        form textarea:focus {
            border-color:#3a4b6d;
            background-color: #f0f8ff;
            outline: none;
        }

        form input[type="number"],
        form input[type="text"],
        form textarea {
            transition: border 0.3s ease, transform 0.3s ease;
        }

        /* Input Field Hover Effects */
        form input[type="text"]:hover,
        form input[type="number"]:hover,
        form select:hover {
            border-color: #3a4b6d;
        }

        form textarea:hover {
            border-color:#3a4b6d;
            transform: scale(1.02);
        }

        /* Button Styling */
        button {
            background-color: #6a4a6e;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #3a4b6d;
            transform: scale(1.05);
        }

        /* Focus and Hover Effects on Form Fields */
        form input[type="text"]:focus,
        form input[type="number"]:focus,
        form select:focus,
        form textarea:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Message Box Styling (if any success/error message) */
        .message {
            background-color:transparent;
            color: #3a4b6d;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
            font-size: 40px;
        }

        .message.error {
            background-color: #dc3545;
        }

        /* Animation for form fade-in */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design for Small Screens */
        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            form input,
            form select,
            form textarea {
                font-size: 14px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>🏥 Add New Treatment</h1>
        <nav>
            <a href="admin_dashboard.php">🏠 Home</a>
            <a href="appointments.php">📅 Manage Appointments</a>
            <a href="verify_certificates.php">📜 Verify Certificates</a>
            <a href="system_settings.php">⚙️ System Settings</a>
            <a href="../logout.php" class="logout">🚪 Logout</a>
        </nav>
    </header>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="add_treatments.php" method="post">
        <label for="name">Treatment Name:</label>
        <input type="text" name="name" id="name" required placeholder="Enter treatment name" />

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="Skin">Skin</option>
            <option value="Hair">Hair</option>
            <option value="Aesthetic">Aesthetic</option>
        </select>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required placeholder="Enter treatment description"></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required placeholder="Enter price" step="0.01" />

        <button type="submit">Add Treatment</button>
    </form>

</body>

</html>

<?php mysqli_close($conn); ?>