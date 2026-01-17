<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../config/config.php';
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                echo "Login successful! Welcome, " . htmlspecialchars($user['username']) . ".";



                if (isset($_POST['remember'])) {
                    setcookie('user_id', $user['id'], time() + (30 * 24 * 60 * 60), '/'); // 30 days
                }
                if ($user['role'] === 'admin') {
                    header('Location: ../pages/grades.html');
                } else {
                    header('Location: ../pages/grades.html');
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
            <form class="login-form" action="" method="post" autocomplete="off" id="login-form">
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="remember"><input type="checkbox" name="remember" id="remember"> Remember me</label>
                <button type="submit" id="login-btn">Hyrje</button>
            </form>
            <p>Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a></p>
        </div>
    </div>
    <footer class="footer-navbar"></footer>
</body>
</html>
