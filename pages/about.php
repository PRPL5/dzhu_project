<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$auth = new Auth(userClass: new User(database: $pdo));
$user = $auth->getCurrentUser();

 // Static departments data since departments table is not available
 $departments = [
     [
         'id' => 1,
         'name' => 'Fakulteti i Inxhinierisë',
         'description' => 'Oferton programe në Shkencën Kompjuterike, Inxhinieri Elektrike, Inxhinieri Mjekanike dhe Inxhinieri Qytetare'
     ],
     [
         'id' => 2,
         'name' => 'Fakulteti i Ekonomisë',
         'description' => 'Specijalizohet në Menaxhimin e Biznesit, Ekonomi, Financa dhe Marketing'
     ],
     [
         'id' => 3,
         'name' => 'Fakulteti i Arteve dhe Shkencave',
         'description' => 'Koveron fushat si Matematika, Fizika, Kimia dhe Shkenca Sociale'
     ],
     [
         'id' => 4,
         'name' => 'Fakulteti i Teknologjisë së Informacionit',
         'description' => 'Fokuson në Inxhinieri Softuerike, Sisteme Informacioni dhe Siguri Kibernetike'
     ],
     [
         'id' => 5,
         'name' => 'Fakulteti i Drejtës',
         'description' => 'Jep arsim dhe trajnim juridik për avokatët e ardhshëm dhe profesionistët juridikë'
     ]
 ];
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>SMIS - Rreth Nesh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='../index.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn" onclick="window.location.href='../index.php'">Kryefaqja</button>
            <button class="menu-btn" onclick="window.location.href='about.php'">Rreth Nesh</button>
            <button class="menu-btn" onclick="window.location.href='studenti.php'">Studentët më të Mirë</button>
            <button class="menu-btn" onclick="window.location.href='contact.php'">Kontakt</button>
            <?php if (!$user): ?>
                <button class="menu-btn" onclick="window.location.href='../public/login.php'">Log In</button>
            <?php else: ?>
                <span style="margin-left:10px;">Përshëndetje, <?= htmlspecialchars($user['username']); ?></span>
                <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="about-hero">
        <div class="hero-content">
            <h1>Rreth Universitetit UBT</h1>
            <p class="hero-subtitle">Fuqizimi i mendjeve, formësimi i të ardhmes përmes inovacionit dhe përsosmërisë</p>
        </div>
    </div>

    <div class="container">
        <div class="about-intro">
            <h2>Miresevini ne Sistemin e Menaxhimit te Studenteve</h2>
            <p>Në Universitetin UBT, ne kombinojmë teknologjinë e përparuar me arsimin e nivelit botëror për të krijuar një mjedis ku studentët përparojnë. Platforma jonë SMIS përfaqëson angazhimin tonë ndaj inovacionit, efikasitetit dhe suksesit të studentëve.</p>
        </div>

        <div class="features-grid">
            <div class="feature-box">
                <h3>Arsim Cilësor</h3>
                <p>Program i nivelit botëror i dizajnuar për të përgatitur studentët për sfidat globale</p>
            </div>
            <div class="feature-box">
                <h3>Inovacion</h3>
                <p>Hulumtime dhe integrim teknologjik në çdo program</p>
            </div>
            <div class="feature-box">
                <h3>Rrjeti Global</h3>
                <p>Bashkëpunime ndërkombëtare dhe programe shkëmbimi në mbarë botën</p>
            </div>
            <div class="feature-box">
                <h3>Suksesi në Karrierë</h3>
                <p>Shërbime të dedikuara për karrierë dhe lidhje të forta me industrinë</p>
            </div>
        </div>

        <div class="mission-vision-section">
            <div class="mv-card">
                <div class="mv-header">Misioni Ynë</div>
                <p>Universiteti UBT është i përkushtuar për të ofruar arsim cilësor dhe për të nxitur inovacionin në teknologji dhe biznes. Sistemi ynë i Menaxhimit të Studentëve (SMIS) ndihmon në thjeshtimin e proceseve administrative dhe përmirësimin e përvojës së të nxënit për studentët tanë.</p>
            </div>
            <div class="mv-card">
                <div class="mv-header">Vizioni Ynë</div>
                <p>Të jemi një institucion udhëheqës në arsimin e lartë, i njohur për shkëlqimin në mësimdhënie, kërkim dhe angazhim komunitar. Përmes SMIS, synojmë të krijojmë një mjedis të pandërprerë dhe efikas për studentët, stafin akademik dhe administrativ.</p>
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
                <div class="stat-label">Anëtarë të Fakultetit</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98%</div>
                <div class="stat-label">Norma e Suksesit</div>
            </div>
        </div>

        <div class="departments">
            <h2>Departamentet Akademike</h2>
            <p class="section-subtitle">Eksploroni gamën tonë të larmishme të programeve të dizajnuara për t'ju përgatitur për të ardhmen</p>
            <div id="departments-list" class="departments-list">
                <?php foreach ($departments as $dept): ?>
                    <div class="department-card">
                        <h3><?= htmlspecialchars($dept['name']); ?></h3>
                        <p><?= htmlspecialchars($dept['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>

</body>
<script src="../js/main.js"></script>
<script src="../js/about.js"></script>
</html>
