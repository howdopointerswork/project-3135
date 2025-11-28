<?php
// Get doctors list for footer - only if db connection exists
$doctors = [];
try {
    if (file_exists(__DIR__ . '/../db.php')) {
        require_once(__DIR__ . '/../db.php');
        if (isset($db) && $db !== null) {
            $doctors = getProfs($db);
        }
    }
} catch (Exception $e) {
    // Handle error silently for footer
    $doctors = [];
}
?>

<!-- Footer partial: include this from pages. -->
<!-- Prefer to add the stylesheet in the site header; fallback link here. -->
<link rel="stylesheet" href="/project-3135/css/footer.css">
<!-- Use Font Awesome CDN CSS so icons load without a kit ID -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<footer>
  <div class="container">

    <div class="footer-top">
      <!-- Footer content -->
      <div class="footer-content">
        <div class="upper-left">
          <h3> About Us </h3>
          <p> WyseCare is dedicated to providing the best healthcare services to our community. Our team of professionals is here to support your health and wellness journey. </p>
        </div>

        <div class="lower-left">
          <h3>Contact Us</h3>
          <div class="phone">
            <a href="#"><i class="fa-solid fa-phone"></i> +1 (555) 123-4567</a>
          </div>
          <div class="email">
            <a href="mailto:freedolphin.haru@gmail.com"><i class="fa-solid fa-envelope"></i> wysecare@mail.ca</a>
          </div>
        </div>
      </div>

      <div class="footer-content">
        <h3>Quick Links</h3>
        <div class="login">
          <a href="/project-3135/login.php" style="color: #ffffff !important; text-decoration: none !important; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-sign-in-alt" style="color: #ffb400 !important;"></i> Login to your WyseCare account</a>
        </div>
        <div class="signup">
          <a href="/project-3135/signup.php" style="color: #ffffff !important; text-decoration: none !important; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-user-plus" style="color: #ffb400 !important;"></i> Sign up for a WyseCare account</a>
        </div>
      </div>

      <div class="footer-content">
        <h3>Our Doctors</h3>
        <ul class="doctors-list">
          <?php if (!empty($doctors)): ?>
            <?php foreach ($doctors as $doctor): ?>
              <li>
                <i class="fas fa-user-md"></i> 
                <?php echo htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8'); ?>
                <span class="specialty"><?php echo htmlspecialchars($doctor['specialty'], ENT_QUOTES, 'UTF-8'); ?></span>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li><i class="fas fa-user-md"></i> Our professional team is here to help you</li>
          <?php endif; ?>
        </ul>
      </div>
    </div> <!-- .footer-top -->

    <div class="bottom">
      <p>Wyse Care 2025. All rights reserved.</p>
    </div>

  </div> <!-- end of class container -->
   
 
  </footer>

</body>
</html>