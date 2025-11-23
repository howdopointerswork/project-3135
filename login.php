
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!-- css here -->
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/auth.css">
        <script src="js/main.js"></script>
    </head>
    
    <body>
        <div class="login-gradient-wrapper">
            <h1 class="auth-title login-title">Login to Your WyseCare Account</h1>
        
            <form action="main.php" method="post" class="signup-form">
                <div class="signup-row">
                    <label class="signup-label" for="username">Username</label>
                    <input class="signup-input auth-input" type="text" id="username" name="username" required>
                </div>

                <div class="signup-row">
                    <label class="signup-label" for="password">Password</label>
                    <input class="signup-input auth-input" type="password" id="password" name="password" required>
                </div>

                <div class="signup-row signup-row--submit">
                    <input class="auth-btn" type="submit" value="Log In" name="action" style="width:280px;">
                </div>
            </form>

            <p style="text-align:center; color: #ffffff;">Don't have an account?</p>

            <form action="main.php" method="post" style="margin:18px auto 0;max-width:280px;text-align:center; padding-bottom:40px;">
                <input class="auth-btn" type="submit" value="Sign Up" name="action">
            </form>
        </div>
    </body>

</html>

<?php
// Calls footer
include 'inc/footer.php';

?>

