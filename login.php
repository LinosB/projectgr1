<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'signup'=> $_SESSION['signup_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error){
    return !empty($error) ? "<p class='error-message'>$error</p>":'';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active': '';
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <script src="login.js" defer></script>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/bf32eed12c.js" crossorigin="anonymous"></script>
    <title>Log in</title>
</head>
<body>
    <div class="container">
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h1>Log In</h1>
                <?= showError($errors['login']); ?>
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input name="username" type="username" placeholder="username" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-lock-open"></i>
                        <input name="password" type="password" placeholder="Password" required>
                    </div>
                </div>
                <p>You don't have an account? <a href="#" onclick="showForm('register-form')">Sign Up</a></p>
                <div class="button-field">    
                    <button type="submit" name="login">Log In</button>
                </div>
            </form>
        </div>

        <div class="form-box <?= isActiveForm('signup', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h1>Sign Up</h1>
                <?= showError($errors['signup']); ?>
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input name="username" type="username" placeholder="Username" required>
                    </div>
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-lock-open"></i>
                        <input name="email" type="email" placeholder="E-Mail" required>
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-lock-open"></i>
                        <input name="password" type="password" placeholder="Password" required>
                    </div>
                    <div class="input-field">
                        <select name="role" required>
                            <option value="">--Select a Role--</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Log In</a></p>
                <div class="button-field">    
                    <button type="submit" name="signup">Sign Up</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>