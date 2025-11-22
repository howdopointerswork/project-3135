<?php
// Newsletter processing for footer signup. This runs when the footer is included on any page.
$newsletter_email = '';
$newsletter_error = '';
$newsletter_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_submit'])) {
  $newsletter_email = trim((string)($_POST['newsletter_email'] ?? ''));

  if ($newsletter_email === '') {
    $newsletter_error = 'Please enter your email address.';
  } elseif (!filter_var($newsletter_email, FILTER_VALIDATE_EMAIL)) {
    $newsletter_error = 'Please enter a valid email address.';
  } else {
    // TODO: persist subscription (DB, file, API). For now show success message.
    $newsletter_success = 'Thanks — a confirmation email has been sent to ' . htmlspecialchars($newsletter_email, ENT_QUOTES, 'UTF-8') . '.';
    // Clear the input on success so the field doesn't show the email again
    $newsletter_email = '';
  }
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
        <ul>
          <li><a href="/project-3135/main.php">Home</a></li>
          <li><a href="/project-3135/about.php">About Us</a></li>
          <li><a href="/project-3135/services.php">Services</a></li>
          <li><a href="/project-3135/contact.php">Contact</a></li>
        </ul>
      </div>

      <div class="footer-content">
        <form class="newsletter-form" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
          <h3>Newsletter Signup</h3>
          <div class="newsletter-row">
            <input class="newsletter-input" type="email" name="newsletter_email" placeholder="Enter your email" required value="<?php echo htmlspecialchars($newsletter_email, ENT_QUOTES, 'UTF-8'); ?>">
            <button class="newsletter-btn" type="submit" name="newsletter_submit" value="1">Subscribe</button>
          </div>
          <p class="newsletter-note">Get updates, tips, and special offers — no spam.</p>
          <?php if ($newsletter_error): ?>
            <p class="newsletter-error"><?php echo htmlspecialchars($newsletter_error, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
          <?php if ($newsletter_success): ?>
            <p class="newsletter-success"><?php echo htmlspecialchars($newsletter_success, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
        </form>
      </div>
    </div> <!-- .footer-top -->

    <div class="bottom">
      <p>Wyse Care 2025. All rights reserved.</p>
    </div>

  </div> <!-- end of class container -->
   
 
  </footer>