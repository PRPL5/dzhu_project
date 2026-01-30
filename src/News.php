<?php

class News {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addNews($title, $content, $image_path, $created_by) {
        try {
            $id = $this->db->insert('news', [
                'title' => $title,
                'content' => $content,
                'image_path' => $image_path,
                'created_by' => $created_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Gabim ne shtimin e lajmit: ' . $e->getMessage());
        }
    }

    public function getNews($id) {
        return $this->db->fetch(
            "SELECT n.*, u.username as created_by_name FROM news n 
             LEFT JOIN user u ON n.created_by = u.id 
             WHERE n.id = ?",
            [$id]
        );
    }

    public function getAllNews() {
        return $this->db->fetchAll(
            "SELECT n.*, u.username as created_by_name FROM news n 
             LEFT JOIN user u ON n.created_by = u.id 
             ORDER BY n.created_at DESC"
        );
    }

    public function getLatestNews($limit = 4) {
        $limit = (int) $limit;
        return $this->db->fetchAll(
            "SELECT n.*, u.username as created_by_name FROM news n 
             LEFT JOIN user u ON n.created_by = u.id 
             ORDER BY n.created_at DESC LIMIT $limit"
        );
    }

    public function updateNews($id, $data) {
        $allowedFields = ['title', 'content', 'image_path'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->update('news', $updateData, 'id = ?', [$id]);
        }
    }

    public function deleteNews($id) {
        $news = $this->getNews($id);
        if ($news) {
            if ($news['image_path'] && file_exists($news['image_path'])) {
                unlink($news['image_path']);
            }
        }
        $this->db->delete('news', 'id = ?', [$id]);
    }

    public function getNewsByUser($user_id) {
        return $this->db->fetchAll(
            "SELECT * FROM news WHERE created_by = ? ORDER BY created_at DESC",
            [$user_id]
        );
    }

    public function searchNews($keyword) {
        return $this->db->fetchAll(
            "SELECT n.*, u.username as created_by_name FROM news n 
             LEFT JOIN user u ON n.created_by = u.id 
             WHERE n.title LIKE ? OR n.content LIKE ? 
             ORDER BY n.created_at DESC",
            ["%{$keyword}%", "%{$keyword}%"]
        );
    }
}
?>
