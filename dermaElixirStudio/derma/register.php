<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Derma Elixir Studio</title>
  <link rel="stylesheet" href="registerstyles.css">
  <style>
    /* New style for placing two input fields in one row */
    .form-row {
      display: flex;
      gap: 1rem;
      justify-content: space-between;
    }

    .form-row .form-group {
      flex: 1;
    }

    /* Make inputs fully responsive inside a form row */
    .form-row .form-group input {
      width: 100%;
    }

    /* Button */
    .btn {
      width: 100%;
      padding: 0.8rem;
      font-size: 1.2rem;
      font-weight: bold;
      color: #fff;
      background: linear-gradient(135deg, #2a3a57 0%, #8e6c88 100%);
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.2s ease;
      /* Smooth transition for all properties */
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
      /* Subtle shadow for depth */
    }

    /* Hover Effect */
    .btn:hover {
      background: linear-gradient(135deg, #2a3a57 0%, #8e6c88 100%);
      transform: translateY(-2px);
      /* Slight lift */
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
      /* Stronger shadow */
    }

    /* Active (Clicked) Effect */
    .btn:active {
      background: linear-gradient(135deg, #2a3a57 0%, #8e6c88 100%);
      transform: translateY(1px);
      /* Push down slightly */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      /* Flatten shadow */
    }
  </style>
</head>

<body style="background: linear-gradient(135deg, #dff6ff, #ffe4e1);">
  <!-- Header -->
  <header class="auth-header">
    <a href="index.php" style="text-decoration: none;"><h1 class="logo" style="text-decoration: none;">✨ 𝐃𝐞𝐫𝐦𝐚 𝐄𝐥𝐢𝐱𝐢𝐫 𝐒𝐭𝐮𝐝𝐢𝐨 ✨</h1></a>
  </header>

  <!-- Register Form Section -->
  <section class="auth-section">
    <h2>Register</h2>
    <!-- Only show inside <form> section -->
    <form action="register_process.php" method="post" enctype="multipart/form-data" class="auth-form">

      <div class="form-row">
        <div class="form-group">
          <label for="first-name">First Name:</label>
          <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required>
        </div>
        <div class="form-group">
          <label for="last-name">Last Name:</label>
          <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="mobile">Mobile #:</label>
          <input type="tel" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
        </div>
        <div class="form-group">
          <label for="cnic">CNIC #:</label>
          <input type="text" id="cnic" name="cnic" placeholder="Enter your CNIC number" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="state">State:</label>
          <input type="text" id="state" name="state" placeholder="Enter your state" required>
        </div>
        <div class="form-group">
          <label for="city">City:</label>
          <input type="text" id="city" name="city" placeholder="Enter your city" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>
        <div class="form-group">
          <label for="confirm-password">Confirm Password:</label>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
        </div>
      </div>

      <div class="form-group">
        <label for="certificate">Upload Clearance Certificate (PDF or Image):</label>
        <input type="file" id="certificate" name="certificate" accept=".pdf,.jpg,.jpeg,.png" required>
      </div>

      <div class="form-group">
        <label for="profile-photo">Upload Profile Photo (JPG, JPEG, PNG):</label>
        <input type="file" id="profile-photo" name="profile-photo" accept=".jpg,.jpeg,.png" required>
      </div>

      <button type="submit" class="btn">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login Here</a></p>
  </section>

  <footer class="footer">
    <p>&copy; 2024 Derma Elixir Studio. All rights reserved.</p>
    <nav class="footer-nav">
      <a href="privacy.php">Privacy Policy</a>
      <a href="terms.php">Terms of Service</a>
      <a href="contact.php">Contact</a>
    </nav>
  </footer>
</body>

</html>