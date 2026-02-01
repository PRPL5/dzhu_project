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
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/studenti.css?v=3">
    <link rel="stylesheet" href="../css/calendar.css?v=3">
    <title>Kalendari Akademik - SMIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="menu-items">
            <button class="menu-btn" onclick="window.location.href='student-details.php'">Paneli i Studentit</button>
            <button class="menu-btn" onclick="window.location.href='orari.php'">Orari</button>
            <button class="menu-btn" onclick="window.location.href='grades.php'">Notat</button>
            <button class="menu-btn" onclick="window.location.href='provimet.php'">Provimet</button>
            <button class="menu-btn" onclick="window.location.href='payments.php'">Pagesat</button>
            <button class="menu-btn" onclick="window.location.href='calendar.php'">Kalendari</button>
            <button class="menu-btn" onclick="window.location.href='news.php'">Lajme</button>
            <?php if ($user): ?>
    
                <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
            <?php else: ?>
                <button class="menu-btn" onclick="window.location.href='../public/login.php'">Log In</button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="calendar-header">
        <h1>Kalendari Akademik 2024/2025</h1>
        <p class="subtitle">Datat dhe afatet e rëndësishme për vitin akademik</p>
    </div>

    <div class="calendar-container">
        <div class="calendar-navigation">
            <button class="nav-btn" onclick="showSemester('fall')">Vjeshtë 2024</button>
            <button class="nav-btn active" onclick="showSemester('spring')">Pranverë 2025</button>
            <button class="nav-btn" onclick="showSemester('summer')">Verë 2025</button>
        </div>

        <div class="semester-view" id="fall">
            <h2>Semestri i Vjeshtës 2024</h2>
        </div>

        <div class="semester-view active" id="spring">
            <h2>Semestri i Pranverës 2025</h2>
        </div>

        <div class="semester-view" id="summer">
            <h2>Sesioni i Verës 2025</h2>
        </div>
        <div class="quick-dates">
            <h2>Referenca e Shpejtë</h2>
            <div class="quick-cards">
                <div class="quick-card">
                    <h3>Data e Ardhshme e Rëndësishme</h3>
                    <p class="date-large">23 Jan 2025</p>
                    <p>Fillimi i Mësimit të Pranverës</p>
                </div>
                <div class="quick-card">
                    <h3>Periudha e Regjistrimit</h3>
                    <p class="date-large">6-20 Jan</p>
                    <p>Semestri i Pranverës</p>
                </div>
                <div class="quick-card">
                    <h3>Periudha e Ardhshme e Provimeve</h3>
                    <p class="date-large">2-7 Qer</p>
                    <p>Provimet Finale të Pranverës</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>

    <script>
        function showSemester(semesterId) {
            const allViews = document.querySelectorAll('.semester-view');
            allViews.forEach(view => view.classList.remove('active'));

            const allBtns = document.querySelectorAll('.nav-btn');
            allBtns.forEach(btn => btn.classList.remove('active'));

            document.getElementById(semesterId).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
    <script src="../js/main.js?v=3"></script>
</body>
</html>