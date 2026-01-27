<?php
require_once '../src/Database.php';
require_once '../src/News.php';

$db = new Database();
$newsModel = new News($db);
$latest = $newsModel->getLatestNews(6);
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lajmet - UBT</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    .news-slider{ max-width:1100px; margin:40px auto; position:relative }
    .news-track{ display:flex; gap:20px; overflow:hidden }
    .news-card{ min-width:300px; background:white; border-radius:12px; padding:20px; box-shadow:0 8px 25px rgba(0,0,0,0.08) }
    .news-card img{ width:100%; height:160px; object-fit:cover; border-radius:8px }
    .slider-btn{ position:absolute; top:50%; transform:translateY(-50%); background:#005ECA; color:#fff; border:none; padding:8px 12px; cursor:pointer }
    .slider-prev{ left:-20px }
    .slider-next{ right:-20px }
    </style>
</head>
<body>
    <nav class="menu">
        <img src="../img/ubt1.png" id="nav-logo" alt="UBT" onclick="window.location.href='../index.php'" style="cursor:pointer">
        <div>
            <button class="menu-btn" onclick="window.location.href='../index.php'">Home</button>
            <button class="menu-btn active" onclick="window.location.href='news.php'">Lajmet</button>
            <button class="menu-btn" onclick="window.location.href='contact.php'">Contact</button>
            <button class="menu-btn" onclick="window.location.href='../public/login.php'">Login</button>
        </div>
    </nav>

    <main class="container">
        <h1>Lajmet</h1>
        <div class="news-slider">
            <button class="slider-btn slider-prev" id="prev">‹</button>
            <div class="news-track" id="track">
                <?php if (empty($latest)): ?>
                    <div>Nuk ka lajme.</div>
                <?php else: foreach ($latest as $n): ?>
                    <div class="news-card">
                        <?php if (!empty($n['image_path'])): ?>
                            <img src="../<?php echo htmlspecialchars($n['image_path']); ?>" alt="">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($n['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars(substr($n['content'],0,250))); ?><?php echo strlen($n['content'])>250? '...':''; ?></p>
                        <div style="font-size:0.9rem;color:#666;margin-top:8px">By <?php echo htmlspecialchars($n['created_by_name'] ?? ''); ?> — <?php echo htmlspecialchars($n['created_at']); ?></div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
            <button class="slider-btn slider-next" id="next">›</button>
        </div>
    </main>

    <footer class="footer"></footer>

    <script>
    const track = document.getElementById('track');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');
    let pos = 0;
    prev.addEventListener('click', ()=>{ pos = Math.max(pos-320,0); track.scrollTo({left:pos, behavior:'smooth'}); });
    next.addEventListener('click', ()=>{ pos = Math.min(pos+320, track.scrollWidth - track.clientWidth); track.scrollTo({left:pos, behavior:'smooth'}); });
    </script>
</body>
</html>
