<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                if ($user['role'] === 'admin') {
                    header('Location: ../admin/dashboard.php');
                } else {
                    header('Location: ../pages/main.html');
                }
                exit;
            } else {
                $error = "Username ose password i gabuar!";
            }
        } catch (Exception $e) {
            $error = "Gabim: " . $e->getMessage();
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
    <title>SMIS</title>
</head>
<body>
    <nav class="navbar">
        <img src="../img/ubt1.png" alt="" id="logo">
    </nav>
    <div class="wrapper">
        <div class="card">
            <img src="../img/ubt1.png" alt="" id="login-logo">
            <form class="login-form" action="#" method="post" autocomplete="off" id="login-form">
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="text" name="username" id="email" placeholder="Username" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit" id="login-btn">Hyrje</button>
            </form>
            <p>Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a></p>
        </div>
    </div>
    <footer class="footer-navbar"></footer>
</body>
<script src="../js/login.js"></script>
</html>
