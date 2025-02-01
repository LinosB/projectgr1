<?php
require_once "config.php";

session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
};


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Admin Page</title>
</head>
<body>
<nav>
            <div class="logo-box">
            <p><b> Admin <span> Panel</span></b></p>
            </div>
            
            <ul>
                <li><a href="admin-page.php" class="active">Home</a></li>
                <li><a href="admin-products.php">Products</a></li>
                <li><a href="admin-social.php">Social Page</a></li>
                <li><a href="users-social.php">Users</a></li>
                <li><a href="admin-messages.php">Messages</a></li>
            </ul>
            <div class="welcome">
            <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
            <a class="btn" onclick="window.location.href='logout.php'">Log Out</a>
            </div>
        </nav>

        <section class="dashboard">
            <h1 class="heading">Dashboard</h1>

            <div class="box-container">
                <div class="box">
                    <?php
                    $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
                    $number_of_products = mysqli_num_rows($select_products);
                    ?>
                    <h3><?php echo $number_of_products; ?></h3>
                    <p>Product(s) Available</p>
                </div>
                <div class="box">
                    <?php
                    $select_posts = mysqli_query($conn, "SELECT * FROM posts") or die('query failed');
                    $number_of_posts = mysqli_num_rows($select_posts);
                    ?>
                    <h3><?php echo $number_of_posts; ?></h3>
                    <p>Post(s) Published</p>
                </div>
                <div class="box">
                    <?php
                    $select_users = mysqli_query($conn, "SELECT * FROM users WHERE role='user'") or die('query failed');
                    $number_of_users = mysqli_num_rows($select_users);
                    ?>
                    <h3><?php echo $number_of_users; ?></h3>
                    <p>User(s)</p>
                </div>
                <div class="box">
                    <?php
                    $select_admins = mysqli_query($conn, "SELECT * FROM users WHERE role='admin'") or die('query failed');
                    $number_of_admins = mysqli_num_rows($select_admins);
                    ?>
                    <h3><?php echo $number_of_admins; ?></h3>
                    <p>Admin(s)</p>
                </div>
                <div class="box">
                    <?php
                    $select_account = mysqli_query($conn, "SELECT * FROM users") or die('query failed');
                    $number_of_account = mysqli_num_rows($select_account);
                    ?>
                    <h3><?php echo $number_of_account; ?></h3>
                    <p>Total Users</p>
                </div>
                <div class="box">
                    <?php
                    $select_messages = mysqli_query($conn, "SELECT * FROM message") or die('query failed');
                    $number_of_messages = mysqli_num_rows($select_messages);
                    ?>
                    <h3><?php echo $number_of_messages; ?></h3>
                    <p>Message(s) Recieved</p>
                </div>
            </div>
        </section>
</body>
</html>