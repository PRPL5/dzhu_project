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
    <title>SMIS - Student Details</title>
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
            <button class="menu-btn" onclick="window.location.href='../index.php'">Logout</button>
        </div>
    </nav>

    <h1>Student Details</h1>
    <div class="student-container">
        <div class="student-info-card" id="student-info">
            <!-- Student info will be loaded dynamically via student-details.js -->
        </div>
        <div class="student-id-placeholder">
            <img src="../img/ubt1.png" alt="Student ID Card" />
            <p>Student ID Card</p>
        </div>
    </div>

    <footer class="footer"></footer>

    <script src="../js/main.js"></script>
    <script src="../js/student-details.js"></script>
</body>
</html>
