<?php

class Message {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function create($name, $email, $subject, $message) {
        try {
            $id = $this->db->insert('messages', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'status' => 'new',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Gabim ne krijimin e mesazhit: ' . $e->getMessage());
        }
    }

    public function read($id = null) {
        if ($id) {
            return $this->db->fetch(
                "SELECT * FROM messages WHERE id = ?",
                [$id]
            );
        } else {
            return $this->db->fetchAll(
                "SELECT * FROM messages ORDER BY created_at DESC"
            );
        }
    }

    public function update($id, $data) {
        $allowedFields = ['name', 'email', 'subject', 'message', 'status'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $this->db->update('messages', $updateData, 'id = ?', [$id]);
        }
    }

    public function delete($id) {
        $this->db->delete('messages', 'id = ?', [$id]);
    }
}
?>