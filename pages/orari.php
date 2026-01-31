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
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/orari.css">
    <title>SMIS - Orari</title>
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
    
    <div class="about-hero">
        <div class="hero-content">
            <h1>Orari i Klases</h1>
            <p class="hero-subtitle">Orari juaj javor i klasave në Universitetin UBT</p>
        </div>
    </div>

    <div class="container">
        <div class="schedule-wrapper">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Koha</th>
                        <th>E Hënë</th>
                        <th>E Martë</th>
                        <th>E Mërkurë</th>
                        <th>E Enjte</th>
                        <th>E Premte</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="time-slot">08:00 - 09:30</td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Shkenca Kompjuterike</strong>
                                <span>Dhomë 301</span>
                                <span>Prof. A. Smith</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Matematika</strong>
                                <span>Dhomë 205</span>
                                <span>Prof. B. Johnson</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Fizika</strong>
                                <span>Dhomë 401</span>
                                <span>Prof. C. Williams</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="time-slot">10:00 - 11:30</td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Sistemet e Bazave të të Dhënave</strong>
                                <span>Dhomë 302</span>
                                <span>Prof. D. Brown</span>
                            </div>
                        </td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Zhvillimi i Uebit</strong>
                                <span>Dhomë 303</span>
                                <span>Prof. E. Davis</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Struktura të Dhënave</strong>
                                <span>Dhomë 304</span>
                                <span>Prof. F. Miller</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                    </tr>
                    <tr>
                        <td class="time-slot">12:00 - 13:30</td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Algoritmet</strong>
                                <span>Dhomë 305</span>
                                <span>Prof. G. Wilson</span>
                            </div>
                        </td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Inxhinieria e Softuerit</strong>
                                <span>Dhomë 306</span>
                                <span>Prof. H. Moore</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Rrjetet Kompjuterike</strong>
                                <span>Dhomë 307</span>
                                <span>Prof. I. Taylor</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="time-slot">14:00 - 15:30</td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Sistemet Operative</strong>
                                <span>Dhomë 308</span>
                                <span>Prof. J. Anderson</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Inteligjenca Artificiale</strong>
                                <span>Dhomë 309</span>
                                <span>Prof. K. Thomas</span>
                            </div>
                        </td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Machine Learning</strong>
                                <span>Dhomë 310</span>
                                <span>Prof. L. Jackson</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                    </tr>
                    <tr>
                        <td class="time-slot">16:00 - 17:30</td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Siguria Kibernetike</strong>
                                <span>Dhomë 311</span>
                                <span>Prof. M. White</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                        <td class="class-cell">
                            <div class="class-info">
                                <strong>Zhvillimi Mobil</strong>
                                <span>Dhomë 312</span>
                                <span>Prof. N. Harris</span>
                            </div>
                        </td>
                        <td class="class-cell empty"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer"></footer>
</body>
</html>
