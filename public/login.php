<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
                $auth = new Auth(new User($pdo), $pdo);
                $auth->login($user, isset($_POST['remember']));
                echo "Login successful! Welcome, " . htmlspecialchars($user['username']) . ".";
                if ($user['role'] === 'admin') {
                    header('Location: ../admin/dashboard.php');
                } else {
                    header('Location: ../pages/studenti.php');
                }
                exit;
            } else {
                $error = "Email ose password i gabuar!";
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
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <title>SMIS - Log In</title>
</head>
<body>
    <nav class="navbar">
        <img src="../img/ubt1.png" alt="" id="logo">
    </nav>
    <div class="wrapper">
        <div class="card">
            <img src="../img/ubt1.png" alt="" id="login-logo">
            <form class="login-form" action="" method="post" autocomplete="off" id="login-form">
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Fjalëkalim" required>
                <label for="remember"><input type="checkbox" name="remember" id="remember"> Më mbaj mend</label>
                <button type="submit" id="login-btn">Log In</button>
            </form>
            <p>Nuk keni llogari? <a href="register.php">Regjistrohu këtu</a></p>
        </div>
    </div>
    <footer class="footer-navbar"></footer>
</body>
</html>
