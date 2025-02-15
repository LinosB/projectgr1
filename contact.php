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
    <link rel="stylesheet" href="css/contact.css">
    <title>Contact Us</title>
</head>
<body>
    <nav>
        <img class="logo" src="photos/logo-white.png" alt="Logo">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="store.php">Store</a></li>
                <li><a href="social.php">Social Page</a></li>
                <li><a href="contact.php" class="active">Contact Us</a></li>
            </ul>
            <div class="welcome">
                <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
                <a onclick="window.location.href='logout.php'">Log Out</a>
            </div>
        </nav>
        <div class="container">
            <form onsubmit="sendEmail(); return false" class="animation">
                <h2>Get In Touch</h2>
                <input type="text" id="name" placeholder="Full Name" required>
                <input type="email" id="email" placeholder="E-Mail" required>
                <input type="number" id="phone" placeholder="Phone Number" required>
                <textarea id="textarea" placeholder="How Can We Help You?" rows="5" required></textarea>
                <button type="submit">Send Message </button>
            </form>
        </div>
        <script src="https://smtpjs.com/v3/smtp.js"></script>
        <script>
            function sendEmail(){
                Email.send({
                    Host : "smtp.elasticemail.com",
                    Username : "linos.berisha15@gmail.com",
                    Password : "430D798CE919E1B1F02F94467DFA15ED0BBC",
                    To : 'linos.berisha14@gmail.com',
                    From : 'linos.berisha14@gmail.com',
                    Subject : "New Contact Request",
                    Body : "Name: "+ document.getElementById("name").value
                        +"<br> Email: "+ document.getElementById("email").value
                        +"<br> Phone Number: "+ document.getElementById("phone").value
                        +"<br> Message: "+ document.getElementById("textarea").value
                }).then(
                message => alert('Message Was Sent Successfully')
                ).catch(
                error => {
                    console.error("Error sending email: ", error);
                    alert("Failed to send the message. Please try again later.");
                }
            );
            }
        </script>
</body>
</html>