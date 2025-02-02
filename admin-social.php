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


class Post {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addPost($name, $caption, $image) {
        $conn = $this->db->getConnection();




        $stmt = $conn->prepare("INSERT INTO posts (username, caption, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $caption, $image);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePost($id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getPosts() {
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT * FROM posts");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}


$db = new Database("localhost", "root", "", "users_db");
$post = new Post($db);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_post'])) {
    $username = $_SESSION['username'];
    $caption = $_POST['caption'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    
    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        if ($post->addPost($username, $caption, $image)) {
            echo "Post added successfully!";
        } else {
            echo "Failed to add post.";
        }
    } else {
        echo "Failed to upload image.";
    }
}



if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $post->deletePost($delete_id);
    header("Location: admin-social.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Social Page-Admin</title>
</head>
<body>
<nav>
    <div class="logo-box">
        <p><b> Admin <span> Panel</span></b></p>
    </div>
    <ul>
        <li><a href="admin-page.php">Home</a></li>
        <li><a href="admin-products.php">Products</a></li>
        <li><a href="admin-social.php" class="active">Social Page</a></li>
        <li><a href="users-social.php">Users</a></li>
        <li><a href="admin-messages.php">Messages</a></li>
    </ul>
    <div class="welcome">
        <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
        <a class="btn" href="logout.php">Log Out</a>
    </div>
</nav>

<section class="add-products">
    <h1 class="heading">Social Page</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <h3>Post Something!</h3>
        <input type="textarea" name="caption" class="box" placeholder="Add a caption..." required>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
        <input type="submit" value="Post" name="add_post" class="btn">
    </form>
</section>

<section class="show-posts">
    <div class="box-container">
        <?php
        $posts = $post->getPosts();
        if (!empty($posts)) {
            foreach ($posts as $p) {
                ?>
                <div class="box">
                    <img src="uploaded_img/<?= $p['image']; ?>" alt="">
                    <div class="username"><b><?= $p['username']; ?>:</b></div>
                    <div class="caption"><?= $p['caption']; ?></div>
                    <a href="admin-social.php?delete=<?= $p['id']; ?>" class="delete-btn">Delete</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No posts added yet!</p>';
        }
        ?>
    </div>
</section>
</body>
</html>