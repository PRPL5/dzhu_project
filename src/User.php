<?php

class User {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function login($username, $password) {
        $user = $this->db->fetch(
            "SELECT * FROM user WHERE username = ?",
            [$username]
        );

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function register($username, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $id = $this->db->insert('user', [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Gabim ne regjistrim: ' . $e->getMessage());
        }
    }

    public function getUserById($id) {
        return $this->db->fetch(
            "SELECT id, username, email, role, created_at FROM user WHERE id = ?",
            [$id]
        );
    }

    public function getUserByUsername($username) {
        return $this->db->fetch(
            "SELECT * FROM user WHERE username = ?",
            [$username]
        );
    }

    public function updateUser($id, $data) {
        $allowedFields = ['email', 'username'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $this->db->update('user', $updateData, 'id = ?', [$id]);
        }
    }

    public function deleteUser($id) {
        $this->db->delete('user', 'id = ?', [$id]);
    }

    public function getAllUsers() {
        return $this->db->fetchAll(
            "SELECT id, username, email, role, created_at FROM user ORDER BY created_at DESC"
        );
    }

    public function getUserRole($id) {
        $user = $this->getUserById($id);
        return $user ? $user['role'] : null;
    }

    public function changeRole($id, $role) {
        if (in_array($role, ['admin', 'user'])) {
            $this->db->update('user', ['role' => $role], 'id = ?', [$id]);
        }
    }

    public function usernameExists($username) {
        $result = $this->db->fetch(
            "SELECT id FROM user WHERE username = ?",
            [$username]
        );
        return $result !== false;
    }

    public function emailExists($email) {
        $result = $this->db->fetch(
            "SELECT id FROM user WHERE email = ?",
            [$email]
        );
        return $result !== false;
    }
}
?>
