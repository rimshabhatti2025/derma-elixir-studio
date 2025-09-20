<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Derma Elixir Studio</title>
  <link rel="stylesheet" href="loginstyles.css">
</head>

<body style="background: linear-gradient(135deg, #e5d9f2, #d4f1f4);">
  <!-- Header -->
  <header class="auth-header">
    <a href="index.php" style="text-decoration: none;"><h1 class="logo">✨ 𝐃𝐞𝐫𝐦𝐚 𝐄𝐥𝐢𝐱𝐢𝐫 𝐒𝐭𝐮𝐝𝐢𝐨 ✨</h1></a>
  </header>

  <!-- Login Form Section -->
  <section class="auth-section">
    <h2>Login</h2>

    <h4 style="color:red;" class="errormessage">

      <?php

      error_reporting(0);

      session_start();

      session_destroy();

      echo $_SESSION['loginmessage'];

      ?>
    </h4>
    <form action="login_process.php" method="post" class="auth-form">
      <div class="form-group">
        <label>Name:</label>
        <input type="text" id="name" name="username" placeholder="Enter your name" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register Here</a></p>
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