<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$auth = new Auth(new User($pdo), $pdo);
$auth->requireLogin();
$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>SMIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div>
        <?php if ($auth->isAdmin()): ?>
            <button class="menu-btn" onclick="window.location.href='../admin/dashboard.php'">Paneli i Menaxhimit</button>
        <?php else: ?>
            <button class="menu-btn" onclick="window.location.href='student-details.php'">Paneli i Studentit</button>
            <button class="menu-btn" onclick="window.location.href='orari.php'">Orari</button>
            <button class="menu-btn" onclick="window.location.href='grades.php'">Notat</button>
            <button class="menu-btn" onclick="window.location.href='provimet.php'">Provimet</button>
            <button class="menu-btn" onclick="window.location.href='payments.php'">Pagesat</button>
            <button class="menu-btn" onclick="window.location.href='calendar.php'">Kalendari</button>
        <?php endif; ?>
        <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
    </div>
    </nav>
    <div class="container">
        <h1>Mirë se vini përsëri<br><?php echo htmlspecialchars($user['username']); ?>!</h1>


        <div class="main">

            <div class="card" onclick="window.location.href='student-details.php'" style="cursor: pointer;">
                 <img src="../img/studenti.jpg" alt="">
                <span class="card-title">Studenti</span>
            </div>

            <div class="card" onclick="window.location.href='provimet.php'" style="cursor: pointer;">
                 <img src="../img/libri.jpg" alt="">
                <span class="card-title">Provimet</span>
            </div>

            <div class="card" onclick="window.location.href='payments.php'" style="cursor: pointer;">
                 <img src="../img/tavolina.jpg" alt="">
                <span class="card-title">Pagesat</span>
            </div>
        </div>

        <div class="info-section">
            <div class="info-text">
                <h2>Lajme nga Shkencat Kompjuterike</h2>
                <p>Te nderuar student ju lajmerojme qe afati i Nentorit do te mbahet me 6 Dhjetor ne UBT Dukagjini Te nderuar student ju lajmerojme qe afati i Nentorit do te mbahet me 6 Dhjetor ne UBT Dukagjini Te nderuar student ju lajmerojme qe afati i Nentorit do te mbahet me 6 Dhjetor ne UBT Dukagjini .</p>
                <button class="learn-more-btn">Mësoni më shumë</button>
            </div>
            <div class="info-image">
                <img src="../img/workspace.jpg" alt="placeholder">
            </div>
        </div>

        <div class="info-section">
            <div class="info-image">
                <img src="../img/qyteti.jpg" alt="placeholder">
            </div>
            <div class="info-text">
                <h2>Informacion Shtesë</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                <button class="learn-more-btn">Mësoni më shumë</button>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>
    
</body>

<script src="../js/main.js"></script>
</html>