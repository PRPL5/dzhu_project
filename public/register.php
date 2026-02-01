<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../config/config.php';
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($firstName && $lastName && $email && $password && $confirmPassword) {
        if ($password !== $confirmPassword) {
            $error = "Fjalëkalimët nuk përputhen!";
        } else {
            try {
                $baseUsername = strtolower($firstName . $lastName);
                $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
                $username = $baseUsername;
                
                $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = "Email ekziston tashmë!";
                } else {
                    $counter = 1;
                    while (true) {
                        $stmt = $pdo->prepare("SELECT id FROM user WHERE username = ?");
                        $stmt->execute([$username]);
                        if (!$stmt->fetch()) break;
                        $username = $baseUsername . $counter;
                        $counter++;
                    }
                    
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO user (username, first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $firstName, $lastName, $email, $hashedPassword, 'user']);
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
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <title>SMIS - Regjistrim</title>
</head>
<body>
    <nav class="navbar">
        <img src="../img/ubt1.png" alt="" id="logo">
    </nav>
    <div class="wrapper">
        <div class="card">
            <img src="../img/ubt1.png" alt="" id="login-logo">
            <form class="login-form" action="" method="post" autocomplete="off" id="register-form">
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="text" name="first_name" id="first_name" placeholder="Emri" required>
                <input type="text" name="last_name" id="last_name" placeholder="Mbiemri" required>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Fjalëkalim" required>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmo Fjalëkalimin" required>
                <button type="submit" id="register-btn">Regjistrohu</button>
            </form>
            <p>Jepni tashmë llogari? <a href="login.php">Log In këtu</a></p>
        </div>
    </div>
    <footer class="footer-navbar"></footer>
</body>
</html>
