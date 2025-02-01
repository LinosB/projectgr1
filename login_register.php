<?php
session_start();
require_once 'config.php';

class Database {
    private $conn;

    public function __construct($host, $user, $pass, $dbname) {
        $this->conn = new mysqli($host, $user, $pass, $dbname);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
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

    public function signup($username, $email, $password, $role) {
        $conn = $this->db->getConnection();
        
        $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['signup_error'] = 'Username or email already exists!';
            $_SESSION['active_form'] = 'signup';
            $stmt->close();
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            session_regenerate_id(true);
            $stmt->close();
            return true;
        }
    }

    public function login($username, $password) {
        $conn = $this->db->getConnection();
        
        $stmt = $conn->prepare("SELECT username, email, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $stmt->close();
                return $user['role'];
            }
        }
        
        $_SESSION['login_error'] = 'Incorrect username or password';
        $_SESSION['active_form'] = 'login';
        $stmt->close();
        return false;
    }
}

$db = new Database('localhost', 'root', '', 'users_db');
$user = new User($db);

if (isset($_POST['signup'])) {
    if ($user->signup($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
        header("Location: login.php");
    } else {
        header("Location: login.php");
    }
    exit();
}


if (isset($_POST['login'])) {
    $role = $user->login($_POST['username'], $_POST['password']);

    if ($role) {
        
        $_SESSION['username'] = $_POST['username']; 

        header("Location: " . ($role === 'admin' ? "admin-page.php" : "home.php"));
    } else {
        $_SESSION['login_error'] = 'Incorrect username or password!';
        header("Location: login.php");
    }
    exit();
}
?>
