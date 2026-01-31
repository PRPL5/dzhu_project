<?php
session_start();
require_once '../src/Database.php';
require_once '../src/Message.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$db = new Database();
$auth = new Auth(new User($db));
// Allow students (user) and professors to view notifications; admins may also view.
$auth->requireLogin();
$role = $auth->getCurrentUserRole();
if (!in_array($role, ['user', 'professor', 'admin'])) {
    header('Location: ../public/login.php');
    exit('Nuk ke qasje në këtë faqe.');
}

$messageModel = new Message($db);
$messages = $messageModel->read();

$message = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $message = $messageModel->read($_GET['id']);
}

// View-only: no create/edit/delete here. Messages are managed in manage-messages.php

// Refresh messages (read-only view)
$messages = $messageModel->read();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesazhet - DZHU Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    /* small page-specific tweaks */
    .container { max-width: 100%; margin: 0 auto; padding: 40px 20px; }
    .announcement-content { white-space: pre-wrap; }
    .message-meta { margin-top:12px; font-size:0.9rem; color:#555; display:flex; gap:12px; }
    </style>
</head>
<body>
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='../index.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn <?php echo $current=='dashboard.php' ? 'active' : ''; ?>" onclick="window.location.href='dashboard.php'">Paneli i Menaxhimit</button>
            <button class="menu-btn <?php echo $current=='manage-news.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-news.php'">Menaxho Lajmet</button>
            <button class="menu-btn <?php echo in_array($current, ['manage-users.php','manage-user.php']) ? 'active' : ''; ?>" onclick="window.location.href='manage-users.php'">Menaxho Përdoruesit</button>
            <button class="menu-btn <?php echo $current=='manage-messages.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-messages.php'">Menaxho Mesazhet</button>
            <button class="menu-btn <?php echo $current=='notifications.php' ? 'active' : ''; ?>" onclick="window.location.href='notifications.php'">Mesazhet</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
        </div>
    </nav>

    <main class="container">
        <h1>Mesazhet</h1>

        <div class="announcements">
            <h2>Njoftimet</h2>
            <p class="section-subtitle">Shikoni mesazhet e fundit nga përdoruesit.</p>
            <?php if (empty($messages)): ?>
                <div class="announcement-item">
                    Nuk ka mesazhe.
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="announcement-item">
                        <div class="announcement-title"><?php echo htmlspecialchars($msg['subject']); ?></div>
                        <div class="announcement-content">
                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                        </div>
                        <div style="margin-top:12px; font-size:0.9rem; color:#555; display:flex; gap:12px;">
                            <div><strong>Emri:</strong> <?php echo htmlspecialchars($msg['name']); ?></div>
                            <div><strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?></div>
                            <div><strong>Status:</strong> <?php echo htmlspecialchars($msg['status']); ?></div>
                            <div style="margin-left:auto;"><em><?php echo htmlspecialchars($msg['created_at']); ?></em></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer"></footer>
</body>
</html>
