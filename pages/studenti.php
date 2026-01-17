<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$auth = new Auth(new User($pdo));
$auth->requireLogin();
$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/studenti.css">
    <title>Studenti</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div>
        <button class="menu-btn" onclick="window.location.href='studenti.php'">Dashboard</button>
        <button class="menu-btn" onclick="window.location.href='orari.php'">Schedule</button>
        <button class="menu-btn" onclick="window.location.href='grades.php'">Grades</button>
        <button class="menu-btn" onclick="window.location.href='provimet.php'">Exams</button>
        <button class="menu-btn" onclick="window.location.href='payments.php'">Payments</button>
        <button class="menu-btn" onclick="window.location.href='calendar.php'">Calendar</button>
        <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Logout</button>
    </div>
    </nav>
    <h1>Mirë se vini <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <div class="student-container">
        <div class="student-info-card">
            <h2>Studenti: <?php echo htmlspecialchars($user['username']); ?></h2>
            <div class="info-row">
                <span class="info-label">ID:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['id']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Llogaria Bankare:</span>
                <span class="info-value">354639586767</span>
            </div>
            <div class="info-row">
                <span class="info-label">NR Kontaktues:</span>
                <span class="info-value">049 676 767</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email Personal:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Emri Prindit:</span>
                <span class="info-value">Lorem Ipsum</span>
            </div>
            <div class="info-row">
                <span class="info-label">Gjinia:</span>
                <span class="info-value">M/F</span>
            </div>
            <div class="info-row">
                <span class="info-label">Vendlindja:</span>
                <span class="info-value">Prishtine</span>
            </div>
        </div>
        <div class="student-id-placeholder">
            <img src="../img/ubt1.png" alt="Student ID Card" />
            <p>Student ID Card</p>
        </div>
    </div>

    <div class="action-buttons">
        <button class="action-btn" onclick="window.location.href='provimet.php'">Provimet</button>
        <button class="action-btn" onclick="window.location.href='orari.php'">Orari</button>
        <button class="action-btn" onclick="window.location.href='payments.php'">Pagesat</button>
    </div>

    <footer class="footer"></footer>
    
</body>

<script src="../js/main.js"></script>
</html>