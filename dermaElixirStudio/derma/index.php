<?php
include 'db_connection.php';

// Fetch reviews
$reviewSql = "SELECT reviews.feedback, reviews.rating, reviews.image_path, patients.first_name, patients.last_name 
              FROM reviews 
              JOIN patients ON reviews.patient_id = patients.id 
              ORDER BY reviews.created_at DESC 
              LIMIT 5";

$reviewResult = $conn->query($reviewSql);

// Fetch settings
$settings = [];
$settingsSql = "SELECT setting_name, setting_value FROM system_settings";
$settingsResult = $conn->query($settingsSql);

if ($settingsResult->num_rows > 0) {
    while ($row = $settingsResult->fetch_assoc()) {
        $settings[$row['setting_name']] = $row['setting_value'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Derma Elixir Studio</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Swiper.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

</head>

<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <h1><a href="#" style="color: white; text-decoration:none;" class="logo">✨ 𝐃𝐞𝐫𝐦𝐚 𝐄𝐥𝐢𝐱𝐢𝐫 𝐒𝐭𝐮𝐝𝐢𝐨 ✨</a></h1>
            <nav class="navbar">
                <a href="about.php"> 📖 About Us</a>
                <a href="services.php">💼 Our Services</a>
                <a href="tests.php">🩺 Tests</a>
                <a href="treatments.php">💊 Treatments</a>
                <a href="reviews.php">⭐ Reviews</a>
                <a href="contact.php">📞 Contact Us</a>
                <a href="search.php">🔍 Search</a> <!-- New -->
            </nav>
            <div class="auth-buttons">
                <button onclick="location.href='login.php';">Login</button>
                <button onclick="location.href='register.php';">Register</button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="hero-section">
        <section class="section-content">
            <div class="content details" style="color: black;">
                <h2 class="title" style="font-size: 40px; margin:20px">
                    𝐖𝐄𝐋𝐂𝐎𝐌𝐄 𝐓𝐎 𝐃𝐄𝐑𝐌𝐀 𝐄𝐋𝐈𝐗𝐈𝐑 𝐒𝐓𝐔𝐃𝐈𝐎
                </h2>
                <p style="font-size: 25px;margin:20px">
                    ✨ Nourish. Glow. Flourish. ✨ <br>
                    Your skin deserves the finest care—embrace beauty with confidence! 💖
                </p>
                <a href="register.php" class="book-appointment" style="margin-left: 200px;">
                    Book an appointment
                </a>
            </div>
        </section>
    </main>


    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="section-content">
            <div class="about-image">
                <img src="images/about.webp" alt="About Derma Elixir Studio">
            </div>
            <div class="about-details">
                <h2 class="section-title">ABOUT US</h2>
                <p>Derma Elixir Studio is a premier skin care clinic dedicated to providing personalized treatments that enhance your natural beauty. Our team of certified dermatologists and aestheticians are committed to delivering exceptional results using the latest technologies and highest quality products.</p>
                <p>We believe in a holistic approach to skin health, combining medical expertise with luxurious pampering to create treatments that are both effective and relaxing. Our clinic maintains the highest standards of hygiene and patient care.</p>
                <div class="social-icons">
                    <a href="#" class="icon"><img src="images/whatsapp.png" alt="WhatsApp"></a>
                    <a href="#" class="icon"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="#" class="icon"><img src="images/twitter.png" alt="Twitter"></a>
                    <a href="#" class="icon"><img src="images/instagram.png" alt="Twitter"></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <h2 class="section-title">OUR SERVICES</h2>
        <div class="services-container">
            <div class="service-card">
                <a href="treatments.php"><img src="images/skin-treatment.webp" alt="Skin Treatments"></a>
                <div class="service-info">
                    <h3>Skin Treatments</h3>
                    <p>Comprehensive solutions for all skin types and conditions, from acne to anti-aging.</p>
                </div>
            </div>
            <div class="service-card">
                <a href="treatments.php"><img src="images/hair.jpg" alt="Hair Treatments"></a>
                <div class="service-info">
                    <h3>Hair Treatments</h3>
                    <p>Specialized therapies for hair loss, scalp conditions, and hair rejuvenation.</p>
                </div>
            </div>
            <div class="service-card">
                <a href="tests.php"><img src="images/lab.jpg" alt="Advanced Diagnostics"></a>
                <div class="service-info">
                    <h3>Advanced Diagnostics</h3>
                    <p>State-of-the-art skin analysis and diagnostic services for personalized treatment plans.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <h2 class="section-title">REVIEWS</h2>
        <div class="swiper testimonials-slider">
            <div class="swiper-wrapper">
                <?php
                if ($reviewResult->num_rows > 0) {
                    while ($row = $reviewResult->fetch_assoc()) {
                        $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                        $feedback = htmlspecialchars($row['feedback']);
                        $imagePath = !empty($row['image_path']) ? 'patients/' . $row['image_path'] : 'images/default-user.jpg';
                ?>
                        <div class="swiper-slide testimonial">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $fullName; ?>" style="width:350px; height:350px; border-radius:50%; object-fit:cover;">
                            <h3><?php echo $fullName; ?></h3>
                            <p>"<?php echo $feedback; ?>"</p>
                        </div>
                <?php
                    }
                } else {
                    echo '<div class="swiper-slide testimonial"><p>No reviews available yet.</p></div>';
                }
                ?>

            </div>
            <div class="swiper-navigation">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <?php $conn->close(); ?>

    <!-- Treatments Section -->
    <section class="treatments-section" id="treatments">
        <h2 class="section-title">OUR TREATMENTS</h2>
        <div class="treatments-grid">
            <div class="treatment-card">
                <a href="treatments.php"> <img src="images/hyperpigmentation.webp" alt="Hyperpigmentation Treatment"></a>
                <div class="treatment-info">
                    <h3>Hyperpigmentation Treatment</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/acne.jpg" alt="Acne Treatment"></a>
                <div class="treatment-info">
                    <h3>Acne Treatment</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/dermal-fillers.jpg" alt="Dermal Fillers"></a>
                <div class="treatment-info">
                    <h3>Dermal Fillers</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/facials.jpg" alt="Professional Facials"></a>
                <div class="treatment-info">
                    <h3>Professional Facials</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/fat-reduction.jpg" alt="Fat Reduction"></a>
                <div class="treatment-info">
                    <h3>Fat Reduction</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/mole-removal.webp" alt="Mole Removal"></a>
                <div class="treatment-info">
                    <h3>Mole Removal</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/laser-hair-removal.webp" alt="Laser Hair Removal"></a>
                <div class="treatment-info">
                    <h3>Laser Hair Removal</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/anti-wrinkle-injections.jpg" alt="Anti-Wrinkle Injections"></a>
                <div class="treatment-info">
                    <h3>Anti-Wrinkle Injections</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/hair-loss-treatments.jpg" alt="Hair Loss Treatments"></a>
                <div class="treatment-info">
                    <h3>Hair Loss Treatments</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/thread-lifting.webp" alt="Thread Lifting"></a>
                <div class="treatment-info">
                    <h3>Thread Lifting</h3>
                </div>
            </div>
            <div class="treatment-card">
                <a href="treatments.php"><img src="images/hair-transplant.webp" alt="Hair Transplant"></a>
                <div class="treatment-info">
                    <h3>Hair Transplant</h3>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section">
        <h2>Contact Us</h2>
        <?php
        echo "<p>Email: " . ($settings['support_email'] ?? 'Not set') . "</p>";
        echo "<p>Phone: " . ($settings['phone_number'] ?? 'Not set') . "</p>";
        ?>

        <p>Address: F-7 Markaz, Islamabad, Pakistan</p>
        <p>Business Hours: Mon-Sat: 09:00 AM - 09:00 PM</p>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-content">
            <h2 class="footer-logo" class="logo">Derma Elixir Studio</h2>
            <p>&copy; 2025 Derma Elixir Studio. All rights reserved.</p>
            <nav class="footer-nav">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
                <a href="contact.php">Contact</a>
            </nav>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>

    <!-- Swiper.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        // const hero = document.querySelector('.hero-section');
        // const backgrounds = [
        //     "images/hero-1.jpg",
        //     "images/hero-2.jpg",
        //     "images/hero-3.jpg",
        //     "images/hero-4.jpg",
        //     "images/hero-5.jpg"
        // ];

        // let current = 0;

        // function changeBackground() {
        //     hero.style.backgroundImage = `url('${backgrounds[current]}')`;
        //     current = (current + 1) % backgrounds.length;
        // }

        // changeBackground(); // set initial background
        // setInterval(changeBackground, 10000); // change every 4 seconds

        //new Swiper(what_to_target, how_to_customize_it);
        var swiper = new Swiper(".testimonials-slider", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".testimonials-slider .swiper-button-next", // ✅ Strict selector
                prevEl: ".testimonials-slider .swiper-button-prev", // ✅ Strict selector
            },
            pagination: {
                el: ".testimonials-slider .swiper-pagination", // ✅ Strict selector
                clickable: true,
            },
        });
    </script>
</body>

</html>
<!-- <section id="hero" class="hero-section">
    <div class="hero-content">
      <h2>𝐖𝐄𝐋𝐂𝐎𝐌𝐄 𝐓𝐎 𝐄𝐋𝐈𝐗𝐈𝐑 𝐒𝐓𝐔𝐃𝐈𝐎</h2>
      <p>✨ Nourish. Glow. Flourish. ✨ <br> Your skin deserves the finest care—embrace beauty with confidence! 💖</p>
      <a href="register.php" class="book-appointment" style="margin-left: 200px;">Book an appointment</a>
    </div>
  </section>

  

 
  <section id="about" class="section">
    <h2>About Us</h2>
    <p>Derma Elixir Studio is a welfare program designed to provide free skin care treatments. Our goal is to help individuals in need by offering professional skin consultations and treatments.</p>
  </section>

  
  <section id="services" class="section">
    <h2>Our Services</h2>
    <ul>
      <li>Professional Skin Consultations</li>
      <li>Customized Skin Care Plans</li>
      <li>State-of-the-Art Equipment</li>
    </ul>
  </section>

 
  <section id="tests" class="section">
    <h2>Tests</h2>
    <p>We provide a wide range of diagnostic tests for skin concerns to ensure accurate treatment recommendations.</p>
  </section>

  
  <section id="treatments" class="section">
    <h2>Skin Treatments</h2>
    <p>Our clinic offers treatments for acne, pigmentation, wrinkles, scars, and more, customized to each patient's needs.</p>
  </section>

  
  <section id="contact" class="section">
    <h2>Contact Us</h2>
    <p>Email: info@dermaelixirstudio.com</p>
    <p>Phone: +92-333-1234567</p>
    <p>Address: F-7 Markaz, Islamabad, Pakistan</p>
  </section>

  <footer class="footer">
    <p>&copy; 2024 Derma Elixir Studio. All rights reserved.</p>
    <nav class="footer-nav">
      <a href="privacy.php">Privacy Policy</a>
      <a href="terms.php">Terms of Service</a>
      <a href="contact.php">Contact</a>
    </nav>
  </footer> -->