<?php

session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <title>Home</title>
</head>
<body>
        <nav>
            <img class="logo" src="photos/logo.png" alt="Logo">
            <ul>
                <li><a href="home.php" class="active">Home</a></li>
                <li><a href="store.php">Store</a></li>
                <li><a href="social.php">Social Page</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <div class="welcome">
                <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
                <a onclick="window.location.href='logout.php'">Log Out</a>
            </div>
        </nav>
        <div class="content">
            <div class="text">
                <h1 class="animation">Welcome <br>To Your Ultimate <br> Fitness Hub!</h1>
                <p class="animation">Whether you're a seasoned gym-goer or just starting your journey,<br> we’ve got you covered with the best gear and community support. <br> Explore top-notch equipment and connect with a passionate <br> community of fitness enthusiasts. Together, we’ll help you crush your <br> goals and redefine your limits!"</p>
            </div>
        <div class="slider animation">
            <div class="slides">
                <img class="slide" src="photos/pic1.jpg" alt="">
                <img class="slide" src="photos/pic2.jpg" alt="">
                <img class="slide" src="photos/pic3.jpg" alt="">
            </div>
            <button class="prev" onclick="prevSlide()"><</button>
            <button class="next" onclick="nextSlide()">></button>
        </div>
    </div>

    
    <script src="index.js"></script>
</body>
</html>