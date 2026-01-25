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
$users = $userModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Përdoruesit - DZHU Admin</title>
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
        <h1>Menaxho Përdoruesit</h1>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roli</th>
                    <th>Krijuar më</th>
                    <th>Veprime</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="manage-user.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-edit">Ndrysho</a>
                        <a href="manage-user.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Jeni të sigurt që dëshironi të fshini këtë përdorues?')">Fshi</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; UBT. Të gjitha të drejtat e rezervuara.</p>
    </footer>
</body>
</html>