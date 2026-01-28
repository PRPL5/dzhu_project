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
    <link rel="stylesheet" href="../css/provimet.css">
    <title>Provimet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn" onclick="window.location.href='student-details.php'">Panou i Studentit</button>
            <button class="menu-btn" onclick="window.location.href='orari.php'">Orari</button>
            <button class="menu-btn" onclick="window.location.href='grades.php'">Notat</button>
            <button class="menu-btn" onclick="window.location.href='provimet.php'">Provimet</button>
            <button class="menu-btn" onclick="window.location.href='payments.php'">Pagesat</button>
            <button class="menu-btn" onclick="window.location.href='calendar.php'">Kalendari</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Dalje</button>
        </div>
    </nav>

    <h1>Provimet e Mbetura</h1>

    <div class="provimet-container">
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filter-card">
                <h2>Filtrat</h2>
                <div class="filter-group">
                    <label for="semestri">Semestri:</label>
                    <select id="semestri" class="filter-select">
                        <option value="">Të gjitha</option>
                        <option value="1">Semestri 1</option>
                        <option value="2">Semestri 2</option>
                        <option value="3">Semestri 3</option>
                        <option value="4">Semestri 4</option>
                        <option value="5">Semestri 5</option>
                        <option value="6">Semestri 6</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="viti">Viti Akademik:</label>
                    <select id="viti" class="filter-select">
                        <option value="">Të gjitha</option>
                        <option value="2024">2024/2025</option>
                        <option value="2023">2023/2024</option>
                        <option value="2022">2022/2023</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="profesori">Profesori:</label>
                    <select id="profesori" class="filter-select">
                        <option value="">Zgjedh Profesorin</option>
                        <option value="prof1">Prof. Dr. Shkelqim Berisha</option>
                        <option value="prof2">Prof. Dr. Elton Boshnjaku</option>
                        <option value="prof3">Prof. Dr. Erzen Talla</option>
                        <option value="prof4">Prof. Dr. Blerim Zylfiue</option>
                        <option value="prof5">Prof. Dr. Blerton Abazi</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="afati">Afati:</label>
                    <select id="afati" class="filter-select">
                        <option value="">Të gjitha</option>
                        <option value="shkurt">Shkurt</option>
                        <option value="prill">Prill</option>
                        <option value="qershor">Qershor</option>
                        <option value="shtator">Shtator</option>
                        <option value="nentor">Nëntor</option>
                    </select>
                </div>
                <button class="filter-btn">Apliko Filtrat</button>
                <button class="reset-btn">Reseto</button>
            </div>
        </div>

        <!-- Exams Section -->
        <div class="exams-section">
            <div class="exams-card">
                <h2>Lista e Provimeve</h2>

                <!-- Exam Item Template -->
                <div class="exam-item">
                    <div class="exam-header">
                        <h3>Algoritmet dhe Strukturat e të Dhënave</h3>
                        <span class="exam-credits">6 ECTS</span>
                    </div>
                    <div class="exam-details">
                        <div class="detail-row">
                            <span class="detail-label">Profesori:</span>
                            <span class="detail-value">Prof. Dr. Shkelqim Berisha</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Semestri:</span>
                            <span class="detail-value">Semestri 2</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Data:</span>
                            <span class="detail-value">15 Dhjetor 2025</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Ora:</span>
                            <span class="detail-value">10:00 - 12:00</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Salla:</span>
                            <span class="detail-value">A-201</span>
                        </div>
                    </div>
                    <button class="register-btn">Paraqit Provimin</button>
                </div>

                <!-- Repeat Exam Items -->
                <div class="exam-item">
                    <div class="exam-header">
                        <h3>Bazat e të Dhënave</h3>
                        <span class="exam-credits">7 ECTS</span>
                    </div>
                    <div class="exam-details">
                        <div class="detail-row">
                            <span class="detail-label">Profesori:</span>
                            <span class="detail-value">Prof. Dr. Elton Boshnjaku</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Semestri:</span>
                            <span class="detail-value">Semestri 3</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Data:</span>
                            <span class="detail-value">18 Dhjetor 2025</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Ora:</span>
                            <span class="detail-value">14:00 - 16:00</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Salla:</span>
                            <span class="detail-value">B-105</span>
                        </div>
                    </div>
                    <button class="register-btn">Paraqit Provimin</button>
                </div>

                <!-- Add remaining exams here similarly -->
            </div>
        </div>
    </div>

    <footer class="footer"></footer>
    <script src="../js/main.js"></script>
</body>
</html>
