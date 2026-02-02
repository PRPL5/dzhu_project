<?php
session_start();
require_once '../src/Database.php';
require_once '../src/Message.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$db = new Database();
$auth = new Auth(new User($db));
$auth->requireAdmin();
$messageModel = new Message($db);
$messages = $messageModel->read();
$newMessages = array_filter($messages, function($msg) { return $msg['status'] == 'new'; });
$messageCount = count($newMessages);

$usersCount = $db->fetch("SELECT COUNT(*) as count FROM user")['count'] ?? 0;
$newsCount = $db->fetch("SELECT COUNT(*) as count FROM news")['count'] ?? 0;
$examsCount = $db->fetch("SELECT COUNT(*) as count FROM exams")['count'] ?? 0;

?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DZHU Admin</title>
    <style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: "Inter", sans-serif;
    height: 100vh;
}

.footer {
    margin-top: 80px;
    width: 100%;
    min-height: 180px;
    background-color: rgb(20, 106, 212);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    padding: 40px 20px;
}

.container {
    max-width: 100%;
    margin: 0 auto;
    padding: 40px 20px;
}

h1 {
    text-align: center;
    font-size: 3rem;
}

#nav-logo {
    width: 120px;
    margin-left: 30px;
    position: absolute;
    left: 0;
}

.menu{
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: row;
    align-items: center;
    background-color: rgb(20, 106, 212);
    justify-content: center;
    height: 130px;
    position: relative;
}

.menu-items {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.menu-btn{
    border:none;
    background-color: inherit;
    padding: 20px 16px;
    color: #fff;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.menu-btn:hover{
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}

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

.dashboard-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-top: 40px;
}

.stat {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
    min-width: 200px;
    flex: 1;
    max-width: 250px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.stat h3 {
    margin: 0 0 10px 0;
    color: #146AD4;
    font-size: 1.2rem;
}

.stat p {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #333;
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
    text-align: center;
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

    .menu-btn {
        width: 100%;
        text-align: left;
        padding: 15px 30px;
        margin: 0;
        border-radius: 0;
    }

    #nav-logo {
        position: relative;
        margin: 0;
        left: auto;
        width: 80px;
    }

    .dashboard-stats {
        flex-direction: column;
        align-items: center;
    }

    .stat {
        width: 100%;
        max-width: none;
    }

    .container {
        padding: 20px 10px;
    }

    h1 {
        font-size: 2rem;
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
        <style>
            .menu-btn.active{ background-color: rgba(255,255,255,0.12); transform: translateY(-2px); }
        </style>

    <main class="container">
        <h1>Paneli i Menaxhimit</h1>
        <p>Mirë se erdhe, Admin!</p>
        <div class="dashboard-stats">
            <div class="stat">
                <h3>Lajmet</h3>
                <p id="news-count"><?php echo $newsCount; ?></p>
            </div>
            <div class="stat">
                <h3>Provimet</h3>
                <p id="exams-count"><?php echo $examsCount; ?></p>
            </div>
            <div class="stat">
                <h3>Përdoruesit</h3>
                <p id="users-count"><?php echo $usersCount; ?></p>
            </div>
            <div class="stat">
                <h3>Mesazhet</h3>
                <p id="messages-count"><?php echo $messageCount; ?></p>
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
