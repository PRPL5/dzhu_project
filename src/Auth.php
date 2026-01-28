<?php

class Auth {
    private $userClass;
    private $pdo;

    public function __construct($userClass, $pdo = null) {
        $this->userClass = $userClass;
        $this->pdo = $pdo;
    }

    public function isLoggedIn() {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            return true;
        }
        if (isset($_COOKIE['user_id']) && $this->pdo) {
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
                $stmt->execute([$_COOKIE['user_id']]);
                $user = $stmt->fetch();
                if ($user) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ];
                    return true;
                }
            } catch (Exception $e) {
            }
        }
        return false;
    }

    public function login($user_data, $remember = false) {
        $_SESSION['user'] = [
            'id' => $user_data['id'],
            'username' => $user_data['username'],
            'email' => $user_data['email'],
            'role' => $user_data['role']
        ];
        
        if ($remember) {
            setcookie('user_id', $user_data['id'], time() + (30 * 24 * 60 * 60), '/'); // 30 days
        }
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['user']);
        setcookie('user_id', '', time() - 3600, '/'); // Delete remember me cookie
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user'];
        }
        return null;
    }

    public function getCurrentUserId() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user']['id'];
        }
        return null;
    }

    public function getCurrentUsername() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user']['username'];
        }
        return null;
    }

    public function getCurrentUserRole() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user']['role'];
        }
        return null;
    }

    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user']['role'] === 'admin';
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ../public/login.php');
            exit('Duhet të hysh për të shikuar këtë faqe.');
        }
    }

    public function requireAdmin() {
        if (!$this->isAdmin()) {
            header('Location: ../public/index.php');
            exit('Nuk ke qasje në këtë faqe.');
        }
    }

    public function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function setSessionTimeout($minutes = 30) {
        if (isset($_SESSION['last_activity'])) {
            $elapsed = time() - $_SESSION['last_activity'];
            if ($elapsed > $minutes * 60) {
                $this->logout();
                header('Location: ../public/login.php?timeout=1');
                exit('Sesioni juaj ka skaduar.');
            }
        }
        $_SESSION['last_activity'] = time();
    }
}
?>
