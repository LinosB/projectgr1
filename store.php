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


class Product {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    
    public function getProducts() {
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT * FROM products");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


$db = new Database("localhost", "root", "", "users_db");
$product = new Product($db);


$products = $product->getProducts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/store.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/bf32eed12c.js" crossorigin="anonymous"></script>
    <title>Store</title>
</head>
<body>
<nav>
    <img class="logo" src="photos/logo.png" alt="Logo">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="store.php" class="active">Store</a></li>
            <li><a href="social.php">Social Page</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <div class="welcome">
            <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
            <a onclick="window.location.href='logout.php'">Log Out</a>
        </div>
</nav>
    <section class="products">
        <div class="card-container">
            <?php
            $products = $product->getProducts();
            if (!empty($products)) {
                foreach ($products as $p) {
                    ?>
                    <div class="card">
                        <img src="uploaded_img/<?= $p['image']; ?>" alt="">
                        <div class="title">
                            <h4><?= $p['name']; ?></h4>
                        </div>
                        <div class="card-content">
                            <h3><?= $p['price']; ?>€</h3>
                            <div class="btn">
                                <button>Add In Cart</button>
                                <i class="fa-solid fa-heart"></i>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
                
        </div>
    </section>

</body>
</html>