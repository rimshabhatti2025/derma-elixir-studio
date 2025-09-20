<?php
include 'db_connection.php'; // your DB connection file

$settings = [];
$sql = "SELECT setting_name, setting_value FROM system_settings";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_name']] = $row['setting_value'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Derma Elixir Studio</title>
  <link rel="stylesheet" href="contactstyles.css">
</head>

<body>
  <!-- Header Section -->
  <header class="header">
    <div class="container">
      <h1 class="logo">✨ 𝐃𝐞𝐫𝐦𝐚 𝐄𝐥𝐢𝐱𝐢𝐫 𝐒𝐭𝐮𝐝𝐢𝐨 ✨</h1>
      <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="services.php">Our Services</a>
        <a href="tests.php">Tests</a>
        <a href="treatments.php">Treatments</a>
        <a href="reviews.php">Reviews</a>
        <a href="contact.php">Contact Us</a>
      </nav>
    </div>
  </header>

  <!-- Contact Us Section -->
  <section id="contact" class="contact-section">
    <div class="container">
      <h2>Contact Us</h2>
      <p>Have questions or want to book an appointment? Reach out to us using the form below or contact us directly!</p>

      <!-- Contact Form -->
      <form action="submit_contact.php" method="post" class="contact-form">
        <div class="form-group">
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" placeholder="Your full name" required>
        </div>
        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" placeholder="Your email address" required>
        </div>
        <div class="form-group">
          <label for="subject">Subject:</label>
          <input type="text" id="subject" name="subject" placeholder="Subject" required>
        </div>
        <div class="form-group">
          <label for="message">Message:</label>
          <textarea id="message" name="message" rows="4" placeholder="Your message" required></textarea>
        </div>
        <center><button type="submit" class="btn" style="padding: 15px 230px;">Send Message</button></center>

      </form>

      <!-- Contact Details -->
      <div class="contact-info">
        <h3>Our Contact Information</h3>
        <?php
        echo "<p>Email: " . ($settings['support_email'] ?? 'Not set') . "</p>";
        echo "<p>Phone: " . ($settings['phone_number'] ?? 'Not set') . "</p>";
        ?>
        <p><strong>Address:</strong> Plot # 34, Blue Area, Islamabad, Pakistan</p>
        <p><strong>Business Hours:</strong> Mon-Sat: 9:00 AM - 6:00 PM</p>
      </div>

      <!-- Google Map -->
      <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=YOUR_GOOGLE_MAP_URL" width="600" height="450" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
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