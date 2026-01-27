<?php
session_start();
require_once '../src/Database.php';
require_once '../src/News.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$db = new Database();
$auth = new Auth(new User($db));
$auth->requireAdmin();

$newsModel = new News($db);
$newsList = $newsModel->getAllNews();

$news = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $news = $newsModel->getNews($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $targetDir = __DIR__ . '/../img/news/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image_path = 'img/news/' . $filename;
            }
        }
        $created_by = $auth->getCurrentUserId();
        $newsModel->addNews($title, $content, $image_path, $created_by);
        header('Location: manage-news.php');
        exit;
    } elseif ($action === 'edit' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $data = ['title' => trim($_POST['title'] ?? ''), 'content' => trim($_POST['content'] ?? '')];
        if (!empty($_FILES['image']['name'])) {
            $targetDir = __DIR__ . '/../img/news/';
            if (!is_dir($targetDir)) { mkdir($targetDir, 0755, true); }
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $data['image_path'] = 'img/news/' . $filename;
            }
        }
        $newsModel->updateNews($id, $data);
        header('Location: manage-news.php');
        exit;
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $newsModel->deleteNews($_POST['id']);
        header('Location: manage-news.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menaxho Lajmet - Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    .container{ padding:40px 20px; }
    .form-group{ margin-bottom:12px }
    .news-table{ width:100%; border-collapse:collapse; margin-top:20px }
    .news-table th, .news-table td{ border:1px solid #ddd; padding:8px }
    .news-form{ max-width:800px; margin:20px 0; padding:16px; background:#f9f9f9; border-radius:8px }
    </style>
</head>
<body>
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='dashboard.php'" style="cursor:pointer;">
        <div>
            <button class="menu-btn <?php echo $current=='dashboard.php' ? 'active' : ''; ?>" onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button class="menu-btn <?php echo $current=='manage-news.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-news.php'">Menaxho Lajmet</button>
            <button class="menu-btn" onclick="window.location.href='manage-users.php'">Menaxho Përdoruesit</button>
            <button class="menu-btn" onclick="window.location.href='manage-messages.php'">Mesazhet</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Dalje</button>
        </div>
    </nav>

    <main class="container">
        <h1>Menaxho Lajmet</h1>

        <div class="news-form">
            <h2><?php echo $news ? 'Ndrysho Lajmin' : 'Krijo Lajm të Ri'; ?></h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $news ? 'edit' : 'create'; ?>">
                <?php if ($news): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($news['id']); ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required value="<?php echo $news ? htmlspecialchars($news['title']) : ''; ?>" style="width:100%; padding:8px">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" rows="6" required style="width:100%; padding:8px"><?php echo $news ? htmlspecialchars($news['content']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Image (optional)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <button class="menu-btn" type="submit"><?php echo $news ? 'Update' : 'Create'; ?></button>
                <?php if ($news): ?><a href="manage-news.php" class="menu-btn">Cancel</a><?php endif; ?>
            </form>
        </div>

        <table class="news-table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>By</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $n): ?>
                <tr>
                    <td><?php echo htmlspecialchars($n['id']); ?></td>
                    <td><?php echo htmlspecialchars($n['title']); ?></td>
                    <td><?php echo htmlspecialchars($n['created_by_name'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($n['created_at']); ?></td>
                    <td>
                        <a class="menu-btn" href="manage-news.php?action=edit&id=<?php echo $n['id']; ?>">Edit</a>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                            <button class="menu-btn" onclick="return confirm('Delete this news?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer class="footer"></footer>
</body>
</html>
