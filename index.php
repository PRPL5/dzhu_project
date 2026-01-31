<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'src/Auth.php';
require_once 'src/User.php';

$auth = new Auth(new User($pdo), $pdo);
$isLoggedIn = $auth->isLoggedIn();
$user = $auth->getCurrentUser();


?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>SMIS - Sistemi i Menaxhimit të Studentëve</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='index.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn" onclick="window.location.href='index.php'">Kryefaqja</button>
            <button class="menu-btn" onclick="window.location.href='pages/about.php'">Rreth Nesh</button>
            <button class="menu-btn" onclick="window.location.href='pages/studenti.php'">Studentët më të Mirë</button>
            <button class="menu-btn" onclick="window.location.href='pages/contact.php'">Kontakt</button>
            <?php if ($isLoggedIn) : ?>
                <?php if ($auth->isAdmin()) : ?>
                    <button class="menu-btn" onclick="window.location.href='admin/dashboard.php'">Paneli i Menaxhimit</button>
                <?php else: ?>
                    <button class="menu-btn" onclick="window.location.href='pages/student-details.php'">Paneli i Studentit</button>
                <?php endif; ?>
                <button class="menu-btn" onclick="window.location.href='public/logout.php'">Log Out</button>
                <span style="color: white; margin-left: 10px;">Mirë se vini, <?php echo htmlspecialchars($user['username']); ?></span>
            <?php else: ?>
                <button class="menu-btn" onclick="window.location.href='public/login.php'">Log In</button>
            <?php endif; ?>
        </div>
    </nav>
    
    <div class="about-hero">
        <div class="hero-content">
            <h1>UBT University SMIS</h1>
            <p class="hero-subtitle">Sistemi Modern i Menaxhimit të Studentëve - Menaxho të gjithçka në një vend</p>
        </div>
    </div>

    <div class="container">
        <div class="about-intro">
            <h2>Mirë se vini në Kampusin tuaj Dixhital</h2>
            <p>Qasuni në gjithçka që ju nevojitet për udhëtimin tuaj akademik në Universitetin UBT. Nga menaxhimi i kurseve deri te oraret e provimeve, të gjitha në një platformë intuitive.</p>
        </div>

        <div class="features-grid">
            <div class="feature-box" onclick="window.location.href='public/login.php'" style="cursor: pointer;">
                <h3>Portali i Studentit</h3>
                <p>Qasuni në panelin tuaj personal, notat, orarin dhe informacionin e pagesave</p>
            </div>
            <div class="feature-box" onclick="window.location.href='pages/about.php'" style="cursor: pointer;">
                <h3>Rreth UBT-së</h3>
                <p>Mësoni rreth misionit, vizionit dhe departamenteve tona akademike</p>
            </div>
            <div class="feature-box" onclick="window.location.href='pages/studenti.php'" style="cursor: pointer;">
                <h3>Studentët më të mirë</h3>
                <p>Duke festuar shkëlqimin akademik dhe arritjet e jashtëzakonshme</p>
            </div>
            <div class="feature-box" onclick="window.location.href='pages/contact.php'" style="cursor: pointer;">
                <h3>Kontaktoni</h3>
                <p>Kontaktoni ekipin tonë të mbështetjes për ndihmë dhe pyetje</p>
            </div>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">5000+</div>
                <div class="stat-label">Studentë Aktivë</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">15+</div>
                <div class="stat-label">Departamentet</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">200+</div>
                <div class="stat-label">Anëtarët e Fakultetit</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98%</div>
                <div class="stat-label">Norma e Suksesit</div>
            </div>
        </div>

        <div class="announcements">
            <h2>Njoftimet e Fundit</h2>
            <p class="section-subtitle">Qëndroni të informuar me lajmet dhe ngjarjet më të fundit nga Universiteti UBT</p>
            <div id="announcements-list"></div>
        </div>
    </div>

    <footer class="footer"></footer>
    
</body>
<script src="js/main.js"></script>
<script src="js/home.js"></script>
</html>
