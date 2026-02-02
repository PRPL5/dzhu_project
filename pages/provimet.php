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
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_exam'])) {
    $examId = (int)$_POST['exam_id'];
    $userId = $user['id'];
    
    $existing = $db->fetch("SELECT id FROM exam_registrations WHERE exam_id = ? AND user_id = ?", [$examId, $userId]);
    
    if ($existing) {
        $errorMessage = "Ju tashmë jeni regjistruar për këtë provim!";
    } else {
        $db->insert('exam_registrations', [
            'exam_id' => $examId,
            'user_id' => $userId
        ]);
        $successMessage = "Provimi u paraqit me sukses!";
    }
}

$exams = $db->fetchAll("SELECT * FROM exams ORDER BY exam_date ASC, exam_time ASC");

$professors = $db->fetchAll("SELECT DISTINCT professor FROM exams WHERE professor IS NOT NULL AND professor != '' ORDER BY professor");

$registeredExamIds = [];
if ($user) {
    $registrations = $db->fetchAll("SELECT exam_id FROM exam_registrations WHERE user_id = ?", [$user['id']]);
    foreach ($registrations as $reg) {
        $registeredExamIds[] = $reg['exam_id'];
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/provimet.css?v=5">
    <title>Provimet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
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
    
    <?php if ($errorMessage): ?>
    <div class="alert alert-error" id="errorAlert">
        <span class="alert-icon">!</span>
        <?php echo $errorMessage; ?>
    </div>
    <?php endif; ?>

    <h1>Provimet e Mbetura</h1>
    
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="my-exams.php" class="view-registered-btn">Shiko Provimet e Paraqitura</a>
    </div>

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
                        <option value="">Të gjithë Profesorët</option>
                        <?php foreach ($professors as $prof): ?>
                        <option value="<?php echo htmlspecialchars($prof['professor']); ?>"><?php echo htmlspecialchars($prof['professor']); ?></option>
                        <?php endforeach; ?>
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
                <button class="filter-btn" onclick="applyFilters()">Apliko Filtrat</button>
                <button class="reset-btn" onclick="resetFilters()">Reseto</button>
            </div>
        </div>

        <!-- Exams Section -->
        <div class="exams-section">
            <div class="exams-card">
                <h2>Lista e Provimeve</h2>

                <?php if (empty($exams)): ?>
                    <p style="text-align: center; color: #666; padding: 30px;">Nuk ka provime të regjistruara për momentin.</p>
                <?php else: ?>
                    <?php foreach ($exams as $exam): 
                        $examMonth = date('n', strtotime($exam['exam_date']));
                        $afati = '';
                        if ($examMonth >= 1 && $examMonth <= 2) $afati = 'shkurt';
                        elseif ($examMonth >= 3 && $examMonth <= 4) $afati = 'prill';
                        elseif ($examMonth >= 5 && $examMonth <= 6) $afati = 'qershor';
                        elseif ($examMonth >= 9 && $examMonth <= 10) $afati = 'shtator';
                        elseif ($examMonth >= 11 && $examMonth <= 12) $afati = 'nentor';
                        
                        $examYear = date('Y', strtotime($exam['exam_date']));
                    ?>
                    <div class="exam-item" 
                         data-professor="<?php echo htmlspecialchars($exam['professor'] ?? ''); ?>"
                         data-afati="<?php echo $afati; ?>"
                         data-year="<?php echo $examYear; ?>">
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
                            <?php if (!empty($exam['description'])): ?>
                            <div class="detail-row">
                                <span class="detail-label">Përshkrimi:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($exam['description']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if (in_array($exam['id'], $registeredExamIds)): ?>
                            <button class="register-btn registered" disabled>✓ I Paraqitur</button>
                        <?php else: ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
                                <button type="submit" name="register_exam" class="register-btn">Paraqit Provimin</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
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
        
        function applyFilters() {
            var profesori = document.getElementById('profesori').value.toLowerCase();
            var afati = document.getElementById('afati').value.toLowerCase();
            var viti = document.getElementById('viti').value;
            
            var examItems = document.querySelectorAll('.exam-item');
            var visibleCount = 0;
            
            examItems.forEach(function(item) {
                var itemProfessor = (item.getAttribute('data-professor') || '').toLowerCase();
                var itemAfati = (item.getAttribute('data-afati') || '').toLowerCase();
                var itemYear = item.getAttribute('data-year') || '';
                
                var showProfessor = !profesori || itemProfessor.includes(profesori);
                var showAfati = !afati || itemAfati === afati;
                var showYear = !viti || itemYear === viti;
                
                if (showProfessor && showAfati && showYear) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            var noResultsMsg = document.getElementById('no-filter-results');
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    var msg = document.createElement('p');
                    msg.id = 'no-filter-results';
                    msg.style.cssText = 'text-align: center; color: #666; padding: 30px; font-size: 1.1rem;';
                    msg.textContent = 'Nuk u gjetën provime me këto filtra.';
                    document.querySelector('.exams-card').appendChild(msg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }
        
        function resetFilters() {
            document.getElementById('semestri').value = '';
            document.getElementById('viti').value = '';
            document.getElementById('profesori').value = '';
            document.getElementById('afati').value = '';
            
            var examItems = document.querySelectorAll('.exam-item');
            examItems.forEach(function(item) {
                item.style.display = 'block';
            });
            
            var noResultsMsg = document.getElementById('no-filter-results');
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }
    </script>
</body>
</html>
