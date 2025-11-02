<?php

//variable for dynamic title
$pageTitle = "Registration";
//variable for dynamic header image
$headerImg = "images/pexels-burak-the-weekender-735869.jpg";

//for including the header from inc folder 
include 'inc/header.php'; 

?>

    <h1>Sign Up</h1>
    <form action="process_signup.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required />
    
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />
    
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required />
    
        <button type="submit">Register</button>
    </form>

