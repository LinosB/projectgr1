<?php

require_once 'config.php';

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


class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    
    public function getUsers() {
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT * FROM users");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    public function deleteUser($id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


$db = new Database("localhost", "root", "", "users_db");
$user = new User($db);


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $user->deleteUser($delete_id);
    header("Location: admin_users.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<nav>
    <div class="logo-box">
        <p><b> Admin <span> Panel</span></b></p>
    </div>
    <ul>
        <li><a href="admin-page.php">Home</a></li>
        <li><a href="admin-products.php">Products</a></li>
        <li><a href="admin-social.php">Social Page</a></li>
        <li><a href="users.php" class="active">Users</a></li>
        <li><a href="admin-messages.php">Messages</a></li>
    </ul>
    <div class="welcome">
        <p>Welcome, <span><?= $_SESSION['username']; ?></span></p>
        <a class="btn" href="logout.php">Log Out</a>
    </div>
</nav>

<section class="users">
    <h1 class="heading"> User Accounts </h1>
    <div class="box-container">
        <?php
        $users = $user->getUsers();
        if (!empty($users)) {
            foreach ($users as $u) {
        ?>
        <div class="box">
            <p> User ID : <span><?= $u['id']; ?></span> </p>
            <p> Username : <span><?= $u['username']; ?></span> </p>
            <p> Email : <span><?= $u['email']; ?></span> </p>
            <p> Role : <span style="color:<?= ($u['role'] == 'admin') ? '#AF1740' : '#000'; ?>"><?= $u['role']; ?></span> </p>
            <a href="admin_users.php?delete=<?= $u['id']; ?>" class="delete-btn">Delete</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No users found!</p>';
        }
        ?>
    </div>
</section>

</body>
</html>
