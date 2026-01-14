<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username && $email && $password && $confirmPassword) {
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
                    $success = "Regjistrim i suksesshëm! <a href='login.php'>Hyri këtu</a>";
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
    <title>Regjistrim - DZHU</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Regjistrim</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Konfirmo Password" required>
            <button type="submit">Regjistrohu</button>
        </form>
        <p>Ke llogari? <a href="login.php">Hyri këtu</a></p>
    </div>
</body>
</html>
