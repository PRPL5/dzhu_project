<?php
session_start();
require_once '../src/Database.php';
require_once '../src/User.php';
require_once '../src/Auth.php';

$db = new Database();
$auth = new Auth(new User($db));
$auth->requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $subject = trim($_POST['subject'] ?? '');
            $examDate = $_POST['exam_date'] ?? '';
            $examTime = $_POST['exam_time'] ?? '';
            $location = trim($_POST['location'] ?? '');
            $professor = trim($_POST['professor'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            if ($subject && $examDate && $examTime) {
                $db->insert('exams', [
                    'subject' => $subject,
                    'exam_date' => $examDate,
                    'exam_time' => $examTime,
                    'location' => $location,
                    'professor' => $professor,
                    'description' => $description
                ]);
                $success = "Provimi u shtua me sukses!";
            } else {
                $error = "Plotëso fushat e detyrueshme!";
            }
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'] ?? 0;
            $subject = trim($_POST['subject'] ?? '');
            $examDate = $_POST['exam_date'] ?? '';
            $examTime = $_POST['exam_time'] ?? '';
            $location = trim($_POST['location'] ?? '');
            $professor = trim($_POST['professor'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            if ($id && $subject && $examDate && $examTime) {
                $db->update('exams', [
                    'subject' => $subject,
                    'exam_date' => $examDate,
                    'exam_time' => $examTime,
                    'location' => $location,
                    'professor' => $professor,
                    'description' => $description
                ], 'id = ?', [$id]);
                $success = "Provimi u përditësua me sukses!";
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->delete('exams', 'id = ?', [$id]);
    header('Location: manage-exams.php?deleted=1');
    exit;
}

if (isset($_GET['deleted'])) {
    $success = "Provimi u fshi me sukses!";
}

$editExam = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editExam = $db->fetch("SELECT * FROM exams WHERE id = ?", [$editId]);
}

$exams = $db->fetchAll("
    SELECT e.*, 
           (SELECT COUNT(*) FROM exam_registrations WHERE exam_id = e.id) as registration_count 
    FROM exams e 
    ORDER BY e.exam_date ASC, e.exam_time ASC
");
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Provimet - DZHU Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
.hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 10px;
    z-index: 1001;
}

.hamburger span {
    width: 25px;
    height: 3px;
    background: #fff;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
}

.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

.menu-items {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.exams-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
    margin-top: 20px;
}

.exam-form {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.exam-form h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #555;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    box-sizing: border-box;
}

.form-group textarea {
    min-height: 80px;
    resize: vertical;
}

.btn {
    padding: 10px 20px;
    background-color: #146AD4;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    margin-right: 10px;
}

.btn:hover {
    background-color: #0D5AB8;
}

.btn-delete {
    background-color: #d9534f;
}

.btn-delete:hover {
    background-color: #c9302c;
}

.btn-edit {
    background-color: #5cb85c;
}

.btn-edit:hover {
    background-color: #4cae4c;
}

.btn-cancel {
    background-color: #6c757d;
}

.btn-cancel:hover {
    background-color: #5a6268;
}

.exams-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.exams-table th,
.exams-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.exams-table th {
    background-color: #146AD4;
    color: white;
    font-weight: 600;
}

.exams-table tr:hover {
    background-color: #f5f5f5;
}

.registration-badge {
    display: inline-block;
    padding: 6px 12px;
    background: linear-gradient(135deg, #28a745 0%, #20803a 100%);
    color: white;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 6px;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.actions {
    display: flex;
    gap: 5px;
}

.actions .btn {
    padding: 6px 12px;
    font-size: 0.85rem;
    margin: 0;
}

footer {
    margin-top: 80px;
    width: 100%;
    min-height: 180px;
    background-color: #146AD4;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    padding: 40px 20px;
}

@media (max-width: 900px) {
    .exams-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hamburger {
        display: flex;
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .menu {
        min-height: 80px;
        padding: 20px;
        position: relative;
    }

    .menu-items {
        position: fixed;
        top: 0;
        left: -100%;
        width: 80%;
        max-width: 300px;
        height: 100vh;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding-top: 100px;
        background-color: rgb(20, 106, 212);
        transition: left 0.3s ease;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    }

    .menu-items.active {
        left: 0;
    }

    #nav-logo {
        position: relative;
        margin: 0;
        left: auto;
        width: 80px;
    }

    .menu-btn {
        width: 100%;
        text-align: left;
        padding: 15px 30px;
        margin: 0;
        border-radius: 0;
    }
}
    </style>
</head>
<body>
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='../index.php'" style="cursor: pointer;">
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="menu-items">
            <button class="menu-btn <?php echo $current=='dashboard.php' ? 'active' : ''; ?>" onclick="window.location.href='dashboard.php'">Paneli i Menaxhimit</button>
            <button class="menu-btn <?php echo $current=='manage-news.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-news.php'">Menaxho Lajmet</button>
            <button class="menu-btn <?php echo $current=='manage-exams.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-exams.php'">Menaxho Provimet</button>
            <button class="menu-btn <?php echo in_array($current, ['manage-users.php','manage-user.php']) ? 'active' : ''; ?>" onclick="window.location.href='manage-users.php'">Menaxho Përdoruesit</button>
            <button class="menu-btn <?php echo $current=='manage-messages.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-messages.php'">Menaxho Mesazhet</button>
            <button class="menu-btn <?php echo $current=='notifications.php' ? 'active' : ''; ?>" onclick="window.location.href='notifications.php'">Mesazhet</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
        </div>
    </nav>

    <main class="container">
        <h1>Menaxho Provimet</h1>
        
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="exams-grid">
            <div class="exam-form">
                <h2><?php echo $editExam ? 'Ndrysho Provimin' : 'Shto Provim të Ri'; ?></h2>
                <form method="post" action="">
                    <input type="hidden" name="action" value="<?php echo $editExam ? 'edit' : 'add'; ?>">
                    <?php if ($editExam): ?>
                        <input type="hidden" name="id" value="<?php echo $editExam['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="subject">Lënda *</label>
                        <input type="text" id="subject" name="subject" required 
                               value="<?php echo $editExam ? htmlspecialchars($editExam['subject']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="exam_date">Data e Provimit *</label>
                        <input type="date" id="exam_date" name="exam_date" required
                               value="<?php echo $editExam ? $editExam['exam_date'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="exam_time">Ora *</label>
                        <input type="time" id="exam_time" name="exam_time" required
                               value="<?php echo $editExam ? $editExam['exam_time'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Lokacioni</label>
                        <input type="text" id="location" name="location"
                               value="<?php echo $editExam ? htmlspecialchars($editExam['location']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="professor">Profesori</label>
                        <input type="text" id="professor" name="professor"
                               value="<?php echo $editExam ? htmlspecialchars($editExam['professor']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Përshkrimi</label>
                        <textarea id="description" name="description"><?php echo $editExam ? htmlspecialchars($editExam['description']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn"><?php echo $editExam ? 'Përditëso' : 'Shto Provimin'; ?></button>
                    <?php if ($editExam): ?>
                        <a href="manage-exams.php" class="btn btn-cancel">Anulo</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div>
                <h2>Lista e Provimeve</h2>
                <?php if (empty($exams)): ?>
                    <p>Nuk ka provime të regjistruara.</p>
                <?php else: ?>
                    <table class="exams-table">
                        <thead>
                            <tr>
                                <th>Lënda</th>
                                <th>Data</th>
                                <th>Ora</th>
                                <th>Lokacioni</th>
                                <th>Profesori</th>
                                <th>Regjistrimet</th>
                                <th>Veprime</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exams as $exam): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($exam['subject']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($exam['exam_date'])); ?></td>
                                <td><?php echo date('H:i', strtotime($exam['exam_time'])); ?></td>
                                <td><?php echo htmlspecialchars($exam['location'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($exam['professor'] ?? '-'); ?></td>
                                <td>
                                    <span class="registration-badge"><?php echo $exam['registration_count']; ?> studentë</span>
                                </td>
                                <td class="actions">
                                    <a href="?edit=<?php echo $exam['id']; ?>" class="btn btn-edit">Ndrysho</a>
                                    <a href="?delete=<?php echo $exam['id']; ?>" class="btn btn-delete" 
                                       onclick="return confirm('Jeni të sigurt që dëshironi të fshini këtë provim?')">Fshi</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; UBT. Të gjitha të drejtat e rezervuara.</p>
    </footer>
    
    <script>
    function toggleMenu() {
        var hamburger = document.querySelector('.hamburger');
        var menuItems = document.querySelector('.menu-items');
        
        if (hamburger && menuItems) {
            hamburger.classList.toggle('active');
            menuItems.classList.toggle('active');
        }
    }
    
    document.addEventListener('click', function(e) {
        var hamburger = document.querySelector('.hamburger');
        var menuItems = document.querySelector('.menu-items');
        
        if (hamburger && menuItems && !hamburger.contains(e.target) && !menuItems.contains(e.target)) {
            hamburger.classList.remove('active');
            menuItems.classList.remove('active');
        }
    });
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            var hamburger = document.querySelector('.hamburger');
            var menuItems = document.querySelector('.menu-items');
            if (hamburger && menuItems) {
                hamburger.classList.remove('active');
                menuItems.classList.remove('active');
            }
        }
    });
    </script>
</body>
</html>
