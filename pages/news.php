<?php
session_start();
require_once '../config/db.php';
require_once '../src/Database.php';
require_once '../src/News.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$db = new Database();
$newsModel = new News($db);
$latest = $newsModel->getLatestNews(10);

$auth = new Auth(new User($pdo), $pdo);
$isLoggedIn = $auth->isLoggedIn();
$user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lajmet - UBT</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    .news-slider-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .news-slider {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        background: #f8f9fa;
        padding: 30px;
    }
    
    .news-track {
        display: flex;
        transition: transform 0.4s ease;
        gap: 20px;
    }
    
    .news-card {
        flex: 0 0 calc(33.333% - 14px);
        min-width: calc(33.333% - 14px);
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .news-card-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .news-card-content {
        padding: 20px;
    }
    
    .news-card h3 {
        font-size: 1.1rem;
        margin: 0 0 10px 0;
    }
    
    .news-card p {
        font-size: 0.9rem;
        color: #666;
        margin: 0 0 15px 0;
    }
    
    .news-card-meta {
        font-size: 0.8rem;
        color: #999;
    }
    
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 45px;
        height: 45px;
        background: #005ECA;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        z-index: 10;
    }
    
    .slider-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .slider-prev { left: -20px; }
    .slider-next { right: -20px; }
    
    .no-news {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }
    
    @media (max-width: 992px) {
        .news-card {
            flex: 0 0 calc(50% - 10px);
            min-width: calc(50% - 10px);
        }
    }
    
    @media (max-width: 768px) {
        .news-card {
            flex: 0 0 100%;
            min-width: 100%;
        }
        .slider-prev { left: 10px; }
        .slider-next { right: 10px; }
    }
    
    .news-hero {
        background: linear-gradient(135deg, #005ECA 0%, #003d82 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .news-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .news-hero p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .section-title {
        text-align: center;
        margin: 50px 0 30px;
        color: #333;
    }
    
    .section-title h2 {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }
    
    .section-title p {
        color: #666;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
    }
    
    .info-card-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .info-card h3 {
        color: #005ECA;
        margin-bottom: 10px;
    }
    
    .info-card p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .newsletter-section {
        background: #f8f9fa;
        padding: 50px 20px;
        text-align: center;
        margin-top: 50px;
    }
    
    .newsletter-section h2 {
        margin-bottom: 15px;
        color: #333;
    }
    
    .newsletter-section p {
        color: #666;
        margin-bottom: 25px;
    }
    
    .newsletter-form {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .newsletter-form input {
        padding: 12px 20px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        width: 300px;
        max-width: 100%;
    }
    
    .newsletter-form button {
        padding: 12px 30px;
        background: #005ECA;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .newsletter-form button:hover {
        background: #004aa3;
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

    .menu {
        position: relative;
    }

    .menu-items {
        display: flex;
        flex-direction: row;
        align-items: center;
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

        .news-hero h1 {
            font-size: 2rem;
        }

        .news-hero p {
            font-size: 1rem;
        }

        .info-cards {
            flex-direction: column;
        }

        .slider-btn {
            display: none;
        }

        .news-card {
            min-width: 100%;
        }
    }
    </style>
</head>
<body>
    <nav class="menu">
        <?php if ($isLoggedIn): ?>
            <img src="../img/ubt1.png" id="nav-logo" alt="UBT" onclick="window.location.href='main.php'" style="cursor:pointer">
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
                <button class="menu-btn active" onclick="window.location.href='news.php'">Lajme</button>
                <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Log Out</button>
            </div>
        <?php else: ?>
            <img src="../img/ubt1.png" id="nav-logo" alt="UBT" onclick="window.location.href='../index.php'" style="cursor:pointer">
            <div class="hamburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="menu-items">
                <button class="menu-btn" onclick="window.location.href='../index.php'">Kryefaqja</button>
                <button class="menu-btn active" onclick="window.location.href='news.php'">Lajmet</button>
                <button class="menu-btn" onclick="window.location.href='contact.php'">Kontakt</button>
                <button class="menu-btn" onclick="window.location.href='../public/login.php'">Log In</button>
            </div>
        <?php endif; ?>
    </nav>

    <div class="news-hero">
        <h1>Lajmet e UBT-së</h1>
        <p>Qëndroni të informuar me lajmet më të fundit nga universiteti ynë. Zbuloni ngjarjet, arritjet dhe mundësitë e reja.</p>
    </div>

    <main class="container">
        <div class="news-slider-container">
            <?php if (empty($latest)): ?>
                <div class="no-news">
                    <h3>Nuk ka lajme për momentin</h3>
                </div>
            <?php else: ?>
                <div class="news-slider" id="newsSlider">
                    <button class="slider-btn slider-prev" id="prevBtn">‹</button>
                    
                    <div class="news-track" id="newsTrack">
                        <?php foreach ($latest as $n): ?>
                            <div class="news-card">
                                <?php if (!empty($n['image_path'])): ?>
                                    <img class="news-card-image" src="../<?php echo htmlspecialchars($n['image_path']); ?>" alt="">
                                <?php else: ?>
                                    <img class="news-card-image" src="../img/ubt.png" alt="">
                                <?php endif; ?>
                                <div class="news-card-content">
                                    <h3><?php echo htmlspecialchars($n['title']); ?></h3>
                                    <p><?php echo htmlspecialchars(substr($n['content'], 0, 150)); ?><?php echo strlen($n['content']) > 150 ? '...' : ''; ?></p>
                                    <div class="news-card-meta"><?php echo date('d M Y', strtotime($n['created_at'])); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="slider-btn slider-next" id="nextBtn">›</button>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="section-title">
            <h2>Çfarë Ofrojmë</h2>
            <p>UBT është lideri në arsimin e lartë në Kosovë</p>
        </div>
        
        <div class="info-cards">
            <div class="info-card">
                <h3>Programe Cilësore</h3>
                <p>Mbi 40 programe studimi të akredituara në nivele Bachelor, Master dhe Doktoraturë.</p>
            </div>
            <div class="info-card">
                <h3>Partneritete Ndërkombëtare</h3>
                <p>Bashkëpunim me mbi 200 universitete nga e gjithë bota përmes programeve Erasmus+.</p>
            </div>
            <div class="info-card">
                <h3>Mundësi Karriere</h3>
                <p>95% e të diplomuarve tanë punësohen brenda 6 muajve pas diplomimit.</p>
            </div>
        </div>
        
        <div class="newsletter-section">
            <h2>Abonohu në Newsletter</h2>
            <p>Merr lajmet e fundit direkt në email-in tënd</p>
            <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Faleminderit për abonimin!');">
                <input type="email" placeholder="Email-i juaj..." required>
                <button type="submit">Abonohu</button>
            </form>
        </div>
    </main>

    <footer class="footer"></footer>

    <script>
    (function() {
        var track = document.getElementById('newsTrack');
        var prevBtn = document.getElementById('prevBtn');
        var nextBtn = document.getElementById('nextBtn');
        if (!track) return;
        
        var cards = track.querySelectorAll('.news-card');
        var index = 0;
        var autoSlideInterval;
        var autoSlideDelay = 2000;
        
        function getPerView() {
            if (window.innerWidth <= 768) return 1;
            if (window.innerWidth <= 992) return 2;
            return 3;
        }
        
        function getMax() {
            return Math.max(0, cards.length - getPerView());
        }
        
        function update() {
            var card = cards[0];
            var offset = index * (card.offsetWidth + 20);
            track.style.transform = 'translateX(-' + offset + 'px)';
            prevBtn.disabled = index === 0;
            nextBtn.disabled = index >= getMax();
        }
        
        function nextSlide() {
            if (index < getMax()) {
                index++;
            } else {
                index = 0; 
            }
            update();
        }
        
        function startAutoSlide() {
            stopAutoSlide();
            autoSlideInterval = setInterval(nextSlide, autoSlideDelay);
        }
        
        function stopAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
        }
        
        prevBtn.onclick = function() {
            if (index > 0) { index--; update(); }
            startAutoSlide(); 
        };
        
        nextBtn.onclick = function() {
            if (index < getMax()) { index++; update(); }
            startAutoSlide();
        };
        
     
        track.parentElement.onmouseenter = stopAutoSlide;
        track.parentElement.onmouseleave = startAutoSlide;
        
        window.onresize = function() {
            index = Math.min(index, getMax());
            update();
        };
        
        update();
        startAutoSlide();
    })();

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
    </script>
</body>
</html>