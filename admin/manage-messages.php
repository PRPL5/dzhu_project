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
$messages = $messageModel->read(); // all messages

$message = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $message = $messageModel->read($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'create') {
            $messageModel->create($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message']);
            header('Location: manage-messages.php');
            exit;
        } elseif ($action == 'edit' && isset($_POST['id'])) {
            $messageModel->update($_POST['id'], $_POST);
            header('Location: manage-messages.php');
            exit;
        } elseif ($action == 'delete' && isset($_POST['id'])) {
            $messageModel->delete($_POST['id']);
            header('Location: manage-messages.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menaxho Mesazhet - DZHU Admin</title>
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

.form-group input, .form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.messages-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.messages-table th, .messages-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.messages-table th {
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

.message-form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}
    </style>
</head>
<body>
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <nav class="menu">
        <img src="../img/ubt1.png" alt="UBT Logo" id="nav-logo" onclick="window.location.href='../index.php'" style="cursor: pointer;">
        <div>
            <button class="menu-btn <?php echo $current=='dashboard.php' ? 'active' : ''; ?>" onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button class="menu-btn <?php echo $current=='manage-products.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-products.php'">Menaxho Produktet</button>
            <button class="menu-btn <?php echo $current=='manage-news.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-news.php'">Menaxho Lajmet</button>
            <button class="menu-btn <?php echo in_array($current, ['manage-users.php','manage-user.php']) ? 'active' : ''; ?>" onclick="window.location.href='manage-users.php'">Menaxho Përdoruesit</button>
            <button class="menu-btn <?php echo $current=='manage-messages.php' ? 'active' : ''; ?>" onclick="window.location.href='manage-messages.php'">Mesazhet</button>
            <button class="menu-btn" onclick="window.location.href='../public/logout.php'">Dalje</button>
        </div>
    </nav>

    <main class="container">
        <h1>Menaxho Mesazhet</h1>
        <table class="messages-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Emri</th>
                    <th>Email</th>
                    <th>Subjekti</th>
                    <th>Mesazhi</th>
                    <th>Statusi</th>
                    <th>Krijuar më</th>
                    <th>Veprime</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                <tr>
                    <td><?php echo htmlspecialchars($msg['id']); ?></td>
                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                    <td><?php echo htmlspecialchars(substr($msg['message'], 0, 50)) . (strlen($msg['message']) > 50 ? '...' : ''); ?></td>
                    <td><?php echo htmlspecialchars($msg['status']); ?></td>
                    <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
                    <td>
                        <a href="manage-messages.php?action=edit&id=<?php echo $msg['id']; ?>" class="btn btn-edit">Ndrysho</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Jeni të sigurt që dëshironi të fshini këtë mesazh?')">Fshi</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="message-form">
            <h2><?php echo $message ? 'Ndrysho Mesazhin' : 'Krijo Mesazh të Ri'; ?></h2>
            <form method="post">
                <input type="hidden" name="action" value="<?php echo $message ? 'edit' : 'create'; ?>">
                <?php if ($message): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($message['id']); ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="name">Emri:</label>
                    <input type="text" id="name" name="name" value="<?php echo $message ? htmlspecialchars($message['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $message ? htmlspecialchars($message['email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subjekti:</label>
                    <input type="text" id="subject" name="subject" value="<?php echo $message ? htmlspecialchars($message['subject']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Mesazhi:</label>
                    <textarea id="message" name="message" rows="5" required><?php echo $message ? htmlspecialchars($message['message']) : ''; ?></textarea>
                </div>
                <?php if ($message): ?>
                <div class="form-group">
                    <label for="status">Statusi:</label>
                    <select id="status" name="status">
                        <option value="new" <?php echo $message['status'] == 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="read" <?php echo $message['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
                        <option value="replied" <?php echo $message['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
                    </select>
                </div>
                <?php endif; ?>
                <button type="submit" class="btn"><?php echo $message ? 'Ndrysho' : 'Krijo'; ?></button>
                <?php if ($message): ?>
                    <a href="manage-messages.php" class="btn btn-cancel">Anulo</a>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; UBT. Të gjitha të drejtat e rezervuara.</p>
    </footer>
</body>
</html>