<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';
require_once '../src/Database.php';
require_once '../src/News.php';
require_once '../src/Message.php';

$database = new Database();
$newsModel = new News($database);
$messageModel = new Message($database);

$auth = new Auth(new User($pdo), $pdo);
$auth->requireLogin();
$user = $auth->getCurrentUser();

$userNews = [];
$userMessages = [];
$joined = '—';

if ($user && !empty($user['id'])) {
    $userNews = $newsModel->getNewsByUser($user['id']);
    $userMessages = $database->fetchAll("SELECT * FROM messages WHERE email = ? ORDER BY created_at DESC", [$user['email']]);
    
    $fullUser = $database->fetch("SELECT created_at FROM user WHERE id = ?", [$user['id']]);
    if ($fullUser && !empty($fullUser['created_at'])) {
        $joined = date('F j, Y', strtotime($fullUser['created_at']));
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/studenti.css">
    <title>SMIS - Paneli i Studentit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn" onclick="window.location.href='student-details.php'">Paneli i Studentit</button>
            <button class="menu-btn" onclick="window.location.href='orari.php'">Orari</button>
            <button class="menu-btn" onclick="window.location.href='grades.php'">Notat</button>
            <button class="menu-btn" onclick="window.location.href='provimet.php'">Provimet</button>
            <button class="menu-btn" onclick="window.location.href='payments.php'">Pagesat</button>
            <button class="menu-btn" onclick="window.location.href='calendar.php'">Kalendari</button>
            <button class="menu-btn" onclick="window.location.href='news.php'">Lajme</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
        </div>
    </nav>

    <h1>Paneli i Studentit</h1>
    <div class="student-container">
        <div class="student-info-card" id="student-info">
            <h2>Profili</h2>
            <div class="info-row">
                <div class="info-label">Emri i Përdoruesit:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['username'] ?? '—'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['email'] ?? '—'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Roli:</div>
                <div class="info-value"><?php echo htmlspecialchars($user['role'] ?? '—'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Anëtar që nga:</div>
                <div class="info-value"><?php echo htmlspecialchars($joined); ?></div>
            </div>

            <div class="action-buttons">
                <button class="action-btn" onclick="window.location.href='grades.php'">Shiko Notat</button>
                <button class="action-btn" onclick="window.location.href='orari.php'">Shiko Orarin</button>
                <button class="action-btn" onclick="window.location.href='provimet.php'">Provimet</button>
            </div>

            <section style="margin-top:30px">
                <h2>Postimet tuaja</h2>
                <?php if (empty($userNews)): ?>
                    <p>Akoma nuk keni postime.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($userNews as $n): ?>
                            <li style="margin-bottom:12px">
                                <strong><?php echo htmlspecialchars($n['title'] ?? 'Pa Titull'); ?></strong>
                                <div style="font-size:0.9rem;color:#666">
                                    <?php echo htmlspecialchars($n['created_at'] ? date('F j, Y', strtotime($n['created_at'])) : ''); ?>
                                </div>
                                <p style="margin-top:6px;color:#333">
                                    <?php echo nl2br(htmlspecialchars(substr($n['content'] ?? '', 0, 220))); ?>
                                    <?php echo strlen($n['content'] ?? '') > 220 ? '...' : ''; ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <section style="margin-top:20px">
                <h2>Mesazhet tuaja</h2>
                <?php if (empty($userMessages)): ?>
                    <p>Nuk u gjetën mesazhe.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($userMessages as $m): ?>
                            <li style="margin-bottom:12px">
                                <strong><?php echo htmlspecialchars($m['subject'] ?? 'Mesazh'); ?></strong>
                                <div style="font-size:0.9rem;color:#666">
                                    <?php echo htmlspecialchars($m['created_at'] ? date('F j, Y H:i', strtotime($m['created_at'])) : ''); ?>
                                </div>
                                <p style="margin-top:6px;color:#333">
                                    <?php echo nl2br(htmlspecialchars(substr($m['message'] ?? '', 0, 220))); ?>
                                    <?php echo strlen($m['message'] ?? '') > 220 ? '...' : ''; ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        </div>

        <div class="student-id-placeholder">
            <img src="../img/ubt1.png" alt="Kartela e Studentit" />
            <p>Kartela e Studentit <?php echo htmlspecialchars($user['username'] ?? 'Student'); ?></p>
            <div style="margin-top:18px;color:#666;font-size:0.95rem">
                Email: <?php echo htmlspecialchars($user['email'] ?? '—'); ?>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>

    <script src="../js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Student dashboard loaded');
        });
    </script>
</body>
</html>
