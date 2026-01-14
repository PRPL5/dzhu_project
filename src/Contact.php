<?php

class Contact {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function saveMessage($name, $email, $subject, $message) {
        try {
            $id = $this->db->insert('message', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'status' => 'new',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Gabim ne ruajtjen e mesazhit: ' . $e->getMessage());
        }
    }

    public function getAllMessages() {
        return $this->db->fetchAll(
            "SELECT * FROM message ORDER BY created_at DESC"
        );
    }

    public function getNewMessages() {
        return $this->db->fetchAll(
            "SELECT * FROM message WHERE status = 'new' ORDER BY created_at DESC"
        );
    }

    public function getMessage($id) {
        return $this->db->fetch(
            "SELECT * FROM message WHERE id = ?",
            [$id]
        );
    }

    public function markAsRead($id) {
        $this->db->update('message', ['status' => 'read'], 'id = ?', [$id]);
    }

    public function markAsReplied($id) {
        $this->db->update('message', ['status' => 'replied'], 'id = ?', [$id]);
    }

    public function deleteMessage($id) {
        $this->db->delete('message', 'id = ?', [$id]);
    }

    public function getMessagesByEmail($email) {
        return $this->db->fetchAll(
            "SELECT * FROM message WHERE email = ? ORDER BY created_at DESC",
            [$email]
        );
    }

    public function getUnreadCount() {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as count FROM message WHERE status = 'new'"
        );
        return $result['count'] ?? 0;
    }
}
?>
