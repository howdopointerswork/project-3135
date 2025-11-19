
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!-- css here -->
        <link rel="stylesheet" href="css/main.css">
        <script src="js/main.js"></script>
    </head>
    
    <body>
        <h1>Log In</h1>
        
        <div id="login">
            <form action="main.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
		<input type="submit" value="Log In" name="action"
>
            </form>

        	<form action="main.php" method="post">
		<input type="submit" value="Sign Up" name="action">
		</form>
    </body>

</html>

