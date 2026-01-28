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
    <link rel="stylesheet" href="../css/payments.css">
    <title>Pagesat - SMIS</title>
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

    <h1>Profili i Studentit</h1>

    <div class="profile-container">
        <div class="academic-info-section">
            <div class="info-card">
                <div class="info-item">
                    <div class="info-content">
                        <span class="info-label">Semestri Aktual</span>
                        <span class="info-value">Semestri 4</span>
                    </div>
                </div>
            </div>
            <div class="info-card">
                <div class="info-item">
                    <div class="info-content">
                        <span class="info-label">Viti Akademik</span>
                        <span class="info-value">2024/2025</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="payment-section">
            <div class="payment-card">
                <h2>Kartela Analitike e Pagesave</h2>
                <div class="payment-summary">
                    <div class="summary-item">
                        <span class="summary-label">Totali i Paguar:</span>
                        <span class="summary-value paid">€1,400.00</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Mbetet për Pagese:</span>
                        <span class="summary-value pending">€880.00</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Totali:</span>
                        <span class="summary-value total">€2,280.00</span>
                    </div>
                </div>

                <div class="payment-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Përshkrimi</th>
                                <th>Drejtimi</th>
                                <th>Kodi Financiar</th>
                                <th>Faturimi</th>
                                <th>Pagesa</th>
                                <th>Mbetja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15 Tetor 2024</td>
                                <td>Viti Akademik 2024/2025</td>
                                <td>Shkenca Kompjuterike dhe Inxhinieri</td>
                                <td>SF-2024-0012345</td>
                                <td>€4,000.00</td>
                                <td>€2,500.00</td>
                                <td>€1,500.00</td>
                            </tr>
                            <tr>
                                <td>20 Tetor 2023</td>
                                <td>Viti Akademik 2023/2024</td>
                                <td>Shkenca Kompjuterike dhe Inxhinieri</td>
                                <td>SF-2024-0012345</td>
                                <td>€3,800.00</td>
                                <td>€3,800.00</td>
                                <td>€0.00</td>
                            </tr>
                            <tr>
                                <td>15 Tetor 2022</td>
                                <td>Viti Akademik 2022/2023</td>
                                <td>Shkenca Kompjuterike dhe Inxhinieri</td>
                                <td>SF-2024-0012345</td>
                                <td>€3,600.00</td>
                                <td>€3,600.00</td>
                                <td>€0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="billing-section">
            <div class="billing-card">
                <h2>Informacione për Faturim</h2>
                <div class="billing-info">
                    <div class="billing-item">
                        <div class="billing-content">
                            <span class="billing-label">Adresa e Faturimit</span>
                            <span class="billing-value">Universiteti për Biznes dhe Teknologji (UBT)</span>
                            <span class="billing-detail">Lagjja Kalabria, p.n. 10000 Prishtinë, Kosovë</span>
                            <span class="billing-detail">Tel: +383 38 541 400</span>
                            <span class="billing-detail">Email: info@ubt-uni.net</span>
                        </div>
                    </div>

                    <div class="billing-item">
                        <div class="billing-content">
                            <span class="billing-label">Numri i Llogarisë Bankare</span>
                            <span class="billing-value">1234567890123456</span>
                            <span class="billing-detail">Banka: ProCredit Bank</span>
                            <span class="billing-detail">SWIFT: MBKOXKPR</span>
                        </div>
                    </div>

                    <div class="billing-item">
                        <div class="billing-content">
                            <span class="billing-label">Kodi Financiar i Studentit</span>
                            <span class="billing-value financial-code">SF-2024-0012345</span>
                            <span class="billing-detail">Përdorni këtë kod për të gjitha pagesat tuaja</span>
                        </div>
                    </div>

                    <div class="billing-item">
                        <div class="billing-content">
                            <span class="billing-label">Numri Fiskal</span>
                            <span class="billing-value">600000000</span>
                            <span class="billing-detail">Numri Unik i Identifikimit (NUI)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>
    <script src="../js/main.js"></script>
</body>
</html>
