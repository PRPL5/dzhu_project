<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';
require_once '../src/Database.php';

$auth = new Auth(new User($pdo));
$auth->requireLogin();
$user = $auth->getCurrentUser();

$db = new Database();

$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_registration'])) {
    $examId = (int)$_POST['exam_id'];
    $db->delete('exam_registrations', 'exam_id = ? AND user_id = ?', [$examId, $user['id']]);
    $successMessage = "Regjistrimi u anulua me sukses!";
}

$registeredExams = $db->fetchAll("
    SELECT e.*, er.registered_at 
    FROM exam_registrations er 
    JOIN exams e ON er.exam_id = e.id 
    WHERE er.user_id = ? 
    ORDER BY e.exam_date ASC, e.exam_time ASC
", [$user['id']]);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/provimet.css?v=5">
    <title>Provimet e Mia - SMIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-right: 15px;
        }
        .back-btn:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
        }
        .cancel-btn {
            width: 100%;
            padding: 14px 30px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .cancel-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71d2a 100%);
            transform: translateY(-2px);
        }
        .registered-date {
            font-size: 0.85rem;
            color: #28a745;
            margin-top: 10px;
            padding: 8px 15px;
            background: #d4edda;
            border-radius: 8px;
            display: inline-block;
        }
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .empty-state h2 {
            color: #666;
            margin-bottom: 15px;
        }
        .empty-state p {
            color: #999;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='main.php'" style="cursor: pointer;">
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="menu-items">
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

    <?php if ($successMessage): ?>
    <div class="alert alert-success" id="successAlert">
        <span class="alert-icon">✓</span>
        <?php echo $successMessage; ?>
    </div>
    <?php endif; ?>

    <h1>Provimet e Paraqitura</h1>
    
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="provimet.php" class="back-btn">← Kthehu te Provimet</a>
    </div>

    <div class="provimet-container" style="justify-content: center;">
        <div class="exams-section" style="max-width: 900px; flex: 1;">
            <?php if (empty($registeredExams)): ?>
                <div class="empty-state">
                    <h2>Nuk keni paraqitur asnjë provim</h2>
                    <p>Shkoni te faqja e provimeve për të paraqitur provimet tuaja.</p>
                    <a href="provimet.php" class="view-registered-btn">Shiko Provimet</a>
                </div>
            <?php else: ?>
                <div class="exams-card">
                    <h2>Provimet e Paraqitura (<?php echo count($registeredExams); ?>)</h2>
                    
                    <?php foreach ($registeredExams as $exam): ?>
                    <div class="exam-item">
                        <div class="exam-header">
                            <h3><?php echo htmlspecialchars($exam['subject']); ?></h3>
                        </div>
                        <div class="exam-details">
                            <?php if (!empty($exam['professor'])): ?>
                            <div class="detail-row">
                                <span class="detail-label">Profesori:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($exam['professor']); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="detail-row">
                                <span class="detail-label">Data:</span>
                                <span class="detail-value"><?php echo date('d F Y', strtotime($exam['exam_date'])); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Ora:</span>
                                <span class="detail-value"><?php echo date('H:i', strtotime($exam['exam_time'])); ?></span>
                            </div>
                            <?php if (!empty($exam['location'])): ?>
                            <div class="detail-row">
                                <span class="detail-label">Salla:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($exam['location']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="registered-date">
                            ✓ Paraqitur më: <?php echo date('d/m/Y H:i', strtotime($exam['registered_at'])); ?>
                        </div>
                        <form method="POST" style="margin-top: 15px;" onsubmit="return confirm('A jeni të sigurt që dëshironi të anuloni regjistrimin?');">
                            <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
                            <button type="submit" name="cancel_registration" class="cancel-btn">Anulo Paraqitjen</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer"></footer>
    <script src="../js/main.js?v=3"></script>
    <script>
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() { alert.remove(); }, 500);
            });
        }, 4000);
    </script>
</body>
</html>
