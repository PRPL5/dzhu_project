<?php
session_start();
require_once '../src/Database.php';
require_once '../src/User.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../public/login.php');
    exit;
}

$db = new Database();
$userModel = new User($db);

$message = '';
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (!$action || !$id) {
    header('Location: manage-users.php');
    exit;
}

$user = $userModel->getUserById($id);
if (!$user) {
    header('Location: manage-users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'edit') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($username && $email) {
            try {
                $userModel->updateUser($id, ['username' => $username, 'email' => $email]);
                $message = 'Përdoruesi u përditësua me sukses!';
                $user = $userModel->getUserById($id); // Refresh data
            } catch (Exception $e) {
                $message = 'Gabim: ' . $e->getMessage();
            }
        } else {
            $message = 'Plotëso të gjitha fushat!';
        }
    } elseif ($action === 'delete') {
        try {
            $userModel->deleteUser($id);
            header('Location: manage-users.php?message=Përdoruesi u fshi me sukses!');
            exit;
        } catch (Exception $e) {
            $message = 'Gabim: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action === 'edit' ? 'Ndrysho' : 'Fshi'; ?> Përdorues - DZHU Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">DZHU Admin</div>
            <ul class="menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-products.php">Menaxho Produktet</a></li>
                <li><a href="manage-news.php">Menaxho Lajmet</a></li>
                <li><a href="manage-users.php" class="active">Menaxho Përdoruesit</a></li>
                <li><a href="view-messages.php">Mesazhet</a></li>
                <li><a href="../public/logout.php">Dalje</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <h1><?php echo $action === 'edit' ? 'Ndrysho Përdorues' : 'Fshi Përdorues'; ?></h1>

        <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($action === 'edit'): ?>
        <form method="POST" class="user-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn">Përditëso</button>
            <a href="manage-users.php" class="btn btn-cancel">Anulo</a>
        </form>
        <?php elseif ($action === 'delete'): ?>
        <p>Jeni të sigurt që dëshironi të fshini përdoruesin <strong><?php echo htmlspecialchars($user['username']); ?></strong>?</p>
        <form method="POST">
            <button type="submit" class="btn btn-delete">Po, Fshi</button>
            <a href="manage-users.php" class="btn btn-cancel">Jo, Anulo</a>
        </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; UBT. Të gjitha të drejtat e rezervuara.</p>
    </footer>
</body>
</html>