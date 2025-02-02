<?php

require_once "config.php";

session_start();
if (!isset($_SESSION['username'])) {
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

    public function addProduct($name, $price, $image) {
        $conn = $this->db->getConnection();


        $stmt = $conn->prepare("SELECT name FROM products WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['productname'] = 'Product name already exists!';
            return false;
        }


        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $price, $image);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getProducts() {
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT * FROM products");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


$db = new Database("localhost", "root", "", "users_db");
$product = new Product($db);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];

    if ($_FILES['image']['size'] > 2000000) {
        $_SESSION['imagesize'] = 'Image size is too large!';
    } else {
        move_uploaded_file($image_tmp_name, "uploaded_img/" . $image);
        if ($product->addProduct($name, $price, $image)) {
            $_SESSION['productadded'] = 'Product added successfully!';
        } else {
            $_SESSION['productfailed'] = "Product couldn't be added!";
        }
    }
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $product->deleteProduct($delete_id);
    header("Location: admin-products.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Products</title>
</head>
<body>
<nav>
    <div class="logo-box">
        <p><b> Admin <span> Panel</span></b></p>
    </div>
    <ul>
        <li><a href="admin-page.php">Home</a></li>
        <li><a href="admin-products.php" class="active">Products</a></li>
        <li><a href="admin-social.php">Social Page</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="admin-messages.php">Messages</a></li>
    </ul>
    <div class="welcome">
        <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
        <a class="btn" href="logout.php">Log Out</a>
    </div>
</nav>

<section class="add-products">
    <h1 class="heading">Shop Products</h1>
    <?= !empty($_SESSION['productname']) ? "<p class='error-message'>{$_SESSION['productname']}</p>" : ''; unset($_SESSION['productname']); ?>
    <?= !empty($_SESSION['imagesize']) ? "<p class='error-message'>{$_SESSION['imagesize']}</p>" : ''; unset($_SESSION['imagesize']); ?>
    <?= !empty($_SESSION['productadded']) ? "<p class='success-message'>{$_SESSION['productadded']}</p>" : ''; unset($_SESSION['productadded']); ?>
    <?= !empty($_SESSION['productfailed']) ? "<p class='error-message'>{$_SESSION['productfailed']}</p>" : ''; unset($_SESSION['productfailed']); ?>

    <form action="" method="post" enctype="multipart/form-data">
        <h3>Add Product</h3>
        <input type="text" name="name" class="box" placeholder="Enter Product Name" required>
        <input type="number" name="price" class="box" placeholder="Enter Product Price" required>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
        <input type="submit" value="Add Product" name="add_product" class="btn">
    </form>
</section>

<section class="show-products">
    <div class="box-container">
        <?php
        $products = $product->getProducts();
        if (!empty($products)) {
            foreach ($products as $p) {
                ?>
                <div class="box">
                    <img src="uploaded_img/<?= $p['image']; ?>" alt="">
                    <div class="name"><?= $p['name']; ?></div>
                    <div class="price"><?= $p['price']; ?>€</div>
                    <a href="admin-products.php?delete=<?= $p['id']; ?>" class="delete-btn">Delete</a>
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