<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$auth = new Auth(userClass: new User(database: $pdo));
$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/best-students.css">
    <title>SMIS - Studentët më të Mirë</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='../index.php'" style="cursor: pointer;">
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="menu-items">
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
            <h1>Studentët më të Mirë</h1>
            <p class="hero-subtitle">Duke festuar shkëlqimin akademik dhe arritjet e jashtëzakonshme</p>
        </div>
    </div>

    <div class="container">
        <div class="about-intro">
            <h2>Studentët më të mirë të Universitetit UBT</h2>
            <p>Ne dëshirojmë të festojmë arritjet akademike të studentëve tanë të zgjedhur, që kanë dalluar për përkushtim, angazhim dhe rezultate jashtëzakonshme në vitin akademik.</p>
        </div>

        <div class="podium-section">
            <div class="podium-place second">
                <div class="rank-badge silver">Vendi i 2-të</div>
                <div class="student-avatar silver">
                    <span class="avatar-text">GT</span>
                </div>
                <h3>Gresa Tahiri</h3>
                <p class="student-major">Menaxhim i Biznesit</p>
                <div class="gpa-display silver">GPA: 3.7</div>
                <div class="achievements">
                    <span class="achievement-badge">Lista e Dekanit</span>
                    <span class="achievement-badge">Nderime të Larta</span>
                </div>
            </div>

            <div class="podium-place first">
                <div class="rank-badge gold">Vendi i 1-të</div>
                <div class="student-avatar gold">
                    <span class="avatar-text">AK</span>
                </div>
                <h3>Arber Krasniqi</h3>
                <p class="student-major">Shkenca Kompjuterike</p>
                <div class="gpa-display gold">GPA: 3.8</div>
                <div class="achievements">
                    <span class="achievement-badge">Valediktorian</span>
                    <span class="achievement-badge">Lista e Dekanit</span>
                    <span class="achievement-badge">Pjesëmarrje e Pakthyeshme</span>
                </div>
            </div>

            <div class="podium-place third">
                <div class="rank-badge bronze">Vendi i 3-të</div>
                <div class="student-avatar bronze">
                    <span class="avatar-text">MB</span>
                </div>
                <h3>Mergim Berisha</h3>
                <p class="student-major">Inxhinieri Softuerike</p>
                <div class="gpa-display bronze">GPA: 3.6</div>
                <div class="achievements">
                    <span class="achievement-badge">Lista e Dekanit</span>
                </div>
            </div>
        </div>

        <div class="honorable-mentions">
            <h2>Zbulime të nderimeshme</h2>
            <div class="mentions-grid">
                <div class="mention-card">
                    <div class="mention-rank">4</div>
                    <div class="mention-info">
                        <h4>Valon Shala</h4>
                        <p>Inxhinieri Elektrike</p>
                        <span class="mention-gpa">GPA: 3.5</span>
                    </div>
                </div>
                <div class="mention-card">
                    <div class="mention-rank">5</div>
                    <div class="mention-info">
                        <h4>Erza Ismajli</h4>
                        <p>Ekonomi</p>
                        <span class="mention-gpa">GPA: 3.4</span>
                    </div>
                </div>
                <div class="mention-card">
                    <div class="mention-rank">6</div>
                    <div class="mention-info">
                        <h4>Drilon Morina</h4>
                        <p>Inxhinieri Mjekanike</p>
                        <span class="mention-gpa">GPA: 3.3</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">100+</div>
                <div class="stat-label">Studentë në Listën e Dekanit</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">95%</div>
                <div class="stat-label">Rata e Punës së Ardhshme</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3.5+</div>
                <div class="stat-label">Mesatarja e GPA-së së Vlefshme</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Drejtimesh të Diversifikuara</div>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>
    
</body>
<script src="../js/main.js"></script>
</html>
