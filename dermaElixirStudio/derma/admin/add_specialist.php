<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Derma Elixir Studio</title>
  <link rel="stylesheet" href="add_specialist.css" />
  <style>
    /* General Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f3f4f6, #ffffff);
      color: #333;
      line-height: 1.6;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Form Section */
    .auth-section {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .auth-section h2 {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      color: #2c3e50;
    }

    /* Form Styles */
    .auth-form {
      width: 100%;
      max-width: 1000px;
      /* Increased max-width for the form */
      padding: 2rem;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 1rem;
      font-weight: bold;
      color: #555;
    }

    .form-group input {
      width: 100%;
      padding: 1rem;
      /* Increased padding for larger inputs */
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus {
      border-color: #3498db;
      box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    }

    /* Button */
    .btn {
      width: 100%;
      padding: 1rem;
      font-size: 1.2rem;
      font-weight: bold;
      color: #fff;
      background:#6a4a6e;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
      background:  #3a4b6d;
    }

    .btn:active {
      transform: translateY(0);
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
        /* Single column on small screens */
      }
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header>
    <h1><a href="add_specialist.php">➕ Add Specialist</a></h1>
    <nav>
      <a href="admin_dashboard.php">🏠 Home</a>
      <a href="appointments.php">📅 Manage Appointments</a>
      <a href="verify_certificates.php">📜 Verify Certificates</a>
      <a href="system_settings.php">⚙️ System Settings</a>
      <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
  </header>

  <!-- Register Form Section -->
  <section class="auth-section">
    <h2>Add Specialists</h2>
    <form action="register_specialist.php" method="post" enctype="multipart/form-data" class="auth-form">
      <div class="form-grid">
        <div class="form-group">
          <label for="first-name">First Name:</label>
          <input type="text" id="first-name" name="first_name" placeholder="Enter first name" required>
        </div>
        <div class="form-group">
          <label for="last-name">Last Name:</label>
          <input type="text" id="last-name" name="last_name" placeholder="Enter last name" required>
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
          <label for="mobile">Mobile #:</label>
          <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" required>
        </div>
        <div class="form-group">
          <label for="cnic">CNIC #:</label>
          <input type="text" id="cnic" name="cnic" placeholder="Enter CNIC number" required>
        </div>
        <div class="form-group">
          <label for="state">State:</label>
          <input type="text" id="state" name="state" placeholder="Enter state" required>
        </div>
        <div class="form-group">
          <label for="city">City:</label>
          <input type="text" id="city" name="city" placeholder="Enter city" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>
        <div class="form-group">
          <label for="confirm-password">Confirm Password:</label>
          <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm password" required>
        </div>
        <div class="form-group">
          <label>Qualification:</label>
          <input type="text" name="qualification" placeholder="Enter Qualification" required>
        </div>
        <div class="form-group">
          <label>Area of Expertise:</label>
          <input type="text" name="area_of_expertise" placeholder="Enter Expertise" required>
        </div>
      </div>
      <div class="form-group">
        <label for="profile_photo">Upload Profile Photo:</label>
        <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png" required>
      </div>


      <button type="submit" class="btn">Add Specialist</button>
    </form>

  </section>

</body>

</html>