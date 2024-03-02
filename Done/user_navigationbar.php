<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLACKOUT CINEMA</title>
    <link rel="stylesheet" href="user_navigationbar.css">
    <script src="user_navigationbar.js"></script>
    <link rel="icon" href="images/blackout.png">
    
</head>

<body>
    <?php
        if(!isset($_SESSION['userid'])) {
            
        }
    
    <div class="navbar">
        <ul>
            <a href="index.html"><img class="logo" src="images/blackout.png"></a>
            <li><a href="showtimes.html" >SHOWTIMES</a></li>
            <li><a href="food_beverage.html">FOOD & BEVERAGES</a></li>
            <li><a href="promotions.html">PROMOTIONS</a></li>
            <li><a href="testimonials.html">TESTIMONIALS</a></li>
            <a href="profile.html" id="profile"><img class="button" src="images/profile.png"></a>
            <a href="register.php" id="register" class="button">REGISTER</a>
            <a href="login.php" id="login" class="button">LOGIN</a>
        </ul>
    </div>

</body>
 
</html>
