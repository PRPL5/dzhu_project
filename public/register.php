<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $username = $firstName . ' ' . $lastName;

    if ($firstName && $lastName && $email && $password && $confirmPassword) {
        if ($password !== $confirmPassword) {
            $error = "Fjalëkalimët nuk përputhen!";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                if ($stmt->fetch()) {
                    $error = "Username ose email ekziston tashmë!";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$username, $email, $hashedPassword, 'user']);
                    header('Location: login.php');
                    exit;
                }
            } catch (Exception $e) {
                $error = "Gabim: " . $e->getMessage();
            }
        }
    } else {
        $error = "Plotëso të gjitha fushat!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <title>SMIS - Register</title>
</head>
<body>
    <nav class="navbar">
        <img src="../img/ubt1.png" alt="" id="logo">
    </nav>
    <div class="wrapper">
        <div class="card">
            <img src="../img/ubt1.png" alt="" id="login-logo">
            <form class="login-form" action="#" method="post" autocomplete="off" id="register-form">
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="text" name="first_name" id="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" required>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" id="register-btn">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    <footer class="footer-navbar"></footer>
</body>
<script src="../js/register.js"></script>
</html>
