<?php
require_once '../src/Database.php';
require_once '../src/News.php';

$db = new Database();
$newsModel = new News($db);
$latest = $newsModel->getLatestNews(10);
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
    </style>
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" id="nav-logo" alt="UBT" onclick="window.location.href='../index.php'" style="cursor:pointer">
        <div>
            <button class="menu-btn" onclick="window.location.href='../index.php'">Kryefaqja</button>
            <button class="menu-btn active" onclick="window.location.href='news.php'">Lajmet</button>
            <button class="menu-btn" onclick="window.location.href='contact.php'">Kontakt</button>
            <button class="menu-btn" onclick="window.location.href='../public/login.php'">Hyrje</button>
        </div>
    </nav>

    <main class="container">
        <h1>Lajmet</h1>
        
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
        
        prevBtn.onclick = function() {
            if (index > 0) { index--; update(); }
        };
        
        nextBtn.onclick = function() {
            if (index < getMax()) { index++; update(); }
        };
        
        window.onresize = function() {
            index = Math.min(index, getMax());
            update();
        };
        
        update();
    })();
    </script>
</body>
</html>
