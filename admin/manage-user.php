<?php
$session_start = session_start();
require_once '../src/Database.php';
require_once '../src/User.php';
require_once '../src/Auth.php';

$db = new Database();
$auth = new Auth(new User($db));
$auth->requireAdmin();

$userModel = new User($db);

$message = '';
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if (!$action || !$id) {
    header('Location: manage-users.php');
    exit;
}

$user = $userModel->getUserById($id);
if (!$user) {
    header('Location: manage-users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'edit') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($username && $email) {
            try {
                $userModel->updateUser($id, ['username' => $username, 'email' => $email]);
                $message = 'Përdoruesi u përditësua me sukses!';
                $user = $userModel->getUserById($id); // Refresh data
            } catch (Exception $e) {
                $message = 'Gabim: ' . $e->getMessage();
            }
        } else {
            $message = 'Plotëso të gjitha fushat!';
        }
    } elseif ($action === 'delete') {
        try {
            $userModel->deleteUser($id);
            header('Location: manage-users.php?message=Përdoruesi u fshi me sukses!');
            exit;
        } catch (Exception $e) {
            $message = 'Gabim: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $action === 'edit' ? 'Ndrysho' : 'Fshi'; ?> Përdorues - DZHU Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
.navbar {
    background-color: #146AD4;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.navbar .logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
}

.navbar .menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.navbar .menu li a {
    color: white;
    text-decoration: none;
    padding: 15px 20px;
    display: block;
    transition: background-color 0.3s ease;
}

.navbar .menu li a:hover,
.navbar .menu li a.active {
    background-color: rgba(255, 255, 255, 0.1);
}

.container {
    max-width: 100%;
    margin: 0 auto;
    padding: 40px 20px;
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

footer p {
    margin: 0;
    font-size: 1rem;
}

/* Additional styles for missing classes */
.btn {
    padding: 10px 20px;
    background-color: #146AD4;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 1rem;
}

.btn:hover {
    background-color: #0D5AB8;
}

.btn-cancel {
    background-color: #ccc;
    color: black;
}

.btn-cancel:hover {
    background-color: #bbb;
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

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.users-table th, .users-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.users-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.message {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.user-form {
    max-width: 500px;
    margin: 0 auto;
}
    </style>
</head>
<body>
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='../index.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn <?php echo $current=='dashboard.php' ? 'active' : ''; ?>" onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button class="menu-btn <?php echo $current=='manage-news.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-news.php'">Menaxho Lajmet</button>
            <button class="menu-btn <?php echo in_array($current, ['manage-users.php','manage-user.php']) ? 'active' : ''; ?>" onclick="window.location.href='manage-users.php'">Menaxho Përdoruesit</button>
            <button class="menu-btn <?php echo $current=='manage-messages.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-messages.php'">Menaxho Mesazhet</button>
            <button class="menu-btn <?php echo $current=='notifications.php' ? 'active' : ''; ?>" onclick="window.location.href='notifications.php'">Notifikimet</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Dalje</button>
        </div>
    </nav>

    <main class="container">
        <h1><?php echo $action === 'edit' ? 'Ndrysho Përdorues' : 'Fshi Përdorues'; ?></h1>

        <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($action === 'edit'): ?>
        <form method="POST" class="user-form">
            <div class="form-group">
                <label for="username">Emri i përdoruesit:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn">Përditëso</button>
            <a href="manage-users.php" class="btn btn-cancel">Anulo</a>
        </form>
        <?php elseif ($action === 'delete'): ?>
        <p>Jeni të sigurt që dëshironi të fshini përdoruesin <strong><?php echo htmlspecialchars($user['username']); ?></strong>?</p>
        <form method="POST">
            <button type="submit" class="btn btn-delete">Po, Fshi</button>
            <a href="manage-users.php" class="btn btn-cancel">Jo, Anulo</a>
        </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; UBT. Të gjitha të drejtat e rezervuara.</p>
    </footer>
</body>
</html>