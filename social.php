<?php
require_once 'config.php';


session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

class Database {
    private $conn;

    public function __construct($host, $user, $password, $dbname) {
        $this->conn = new mysqli('localhost', 'root', '', 'users_db');
        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}


class Post {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    
    public function getPosts() {
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT * FROM posts");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


$db = new Database("localhost", "root", "", "users_db");
$post = new Post($db);


$posts = $post->getPosts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bf32eed12c.js" crossorigin="anonymous"></script>
    <title>Social Page</title>
</head>
<body>
    <nav>
    <img class="logo" src="photos/logo.png" alt="Logo">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="store.php">Store</a></li>
            <li><a href="social.php" class="active">Social Page</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <div class="welcome">
            <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
            <a onclick="window.location.href='logout.php'">Log Out</a>
        </div>
    </nav>
            <div class="card-container">
            <?php
            $posts = $post->getPosts();
            if (!empty($posts)) {
                foreach ($posts as $p) {
                    ?>
                    <div class="card">
                    <img src="uploaded_img/<?= $p['image']; ?>" alt="">
                    <div class="user">
                        <i class="fa-regular fa-user"></i>
                        <h4><?= $p['username']; ?>:</h4>
                    </div>
                    <div class="card-content">
                        <p><?= $p['caption']; ?>"</p>
                        <div class="likes">
                            <i class="fa-solid fa-thumbs-up"></i>
                            <i class="fa-solid fa-thumbs-down"></i>
                            <i class="fa-solid fa-comment"></i>
                            <p>1 hour ago</p> 
                        </div>
                    </div>
                </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No posts added yet!</p>';
            }
            ?>    
                
            </div>
</body>
</html>