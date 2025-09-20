<?php
// database connection
include 'db_connection.php';

// Fetch reviews with patient name (optional join with patient table if needed)
$sql = "SELECT r.*, p.first_name, p.last_name 
        FROM reviews r
        JOIN patients p ON r.patient_id = p.id
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews - Derma Elixir Studio</title>
  <link rel="stylesheet" href="reviewsstyles.css">
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

  <!-- Reviews Section -->
  <section id="reviews" class="reviews-section">
    <div class="container">
      <h2>What Our Clients Say</h2>
      <p>We value feedback from our clients. Here's what some of our happy clients have to say about their experiences with our services.</p>

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="review">
            <h3><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h3>
            <p class="rating"><?= str_repeat("⭐", $row['rating']) ?></p>
            <p>"<?= htmlspecialchars($row['feedback']) ?>"</p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No reviews found.</p>
      <?php endif; ?>

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
