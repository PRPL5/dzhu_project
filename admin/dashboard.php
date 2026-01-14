<?php
session_start();
require_once '../config/db.php';


?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DZHU Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">DZHU Admin</div>
            <ul class="menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="manage-products.php">Menaxho Produktet</a></li>
                <li><a href="manage-news.php">Menaxho Lajmet</a></li>
                <li><a href="manage-users.php">Menaxho Përdoruesit</a></li>
                <li><a href="view-messages.php">Mesazhet</a></li>
                <li><a href="../public/logout.php">Dalje</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <h1>Dashboard</h1>
        <p>Mirë se vini, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
        <div class="dashboard-stats">
            <div class="stat">
                <h3>Produktet</h3>
                <p id="products-count">0</p>
            </div>
            <div class="stat">
                <h3>Lajmet</h3>
                <p id="news-count">0</p>
            </div>
            <div class="stat">
                <h3>Përdoruesit</h3>
                <p id="users-count">0</p>
            </div>
            <div class="stat">
                <h3>Mesazhet</h3>
                <p id="messages-count">0</p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; UBT. Të gjitha të drejtat e rezervuara.</p>
    </footer>
</body>
</html>
