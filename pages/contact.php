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
    <link rel="stylesheet" href="../css/styles.css">
    <title>SMIS - Contact</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn" onclick="window.location.href='main.php'">Home</button>
            <button class="menu-btn" onclick="window.location.href='about.php'">About Us</button>
            <button class="menu-btn" onclick="window.location.href='studenti.php'">Top Students</button>
            <button class="menu-btn" onclick="window.location.href='contact.php'">Contact</button>
            <?php if ($user): ?>
                <span style="margin-left:10px;">Hello, <?= htmlspecialchars($user['name']); ?></span>
                <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Logout</button>
            <?php else: ?>
                <button class="menu-btn" onclick="window.location.href='../public/login.php'">Login</button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="about-hero">
        <div class="hero-content">
            <h1>Na Kontaktoni</h1>
            <p class="hero-subtitle">Kontaktoni Universitetin UBT - Ne jemi këtu për t'ju ndihmuar</p>
        </div>
    </div>

    <div class="container">
        <div class="contact-section">
            <div class="contact-form">
                <form id="contact-form" method="post" action="send_contact.php">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                    <button type="submit" class="learn-more-btn">Send Message</button>
                </form>
            </div>
            <div class="contact-info">
                <h2>Informacioni i Kontaktit</h2>
                <p><strong>Adresa:</strong> Prishtina, Kosovo</p>
                <p><strong>Telefoni:</strong> +383 38 541 400</p>
                <p><strong>Email:</strong> info@ubt-uni.net</p>
                <p><strong>Website:</strong> www.ubt-uni.net</p>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>

</body>
<script src="../js/contact.js"></script>
</html>
