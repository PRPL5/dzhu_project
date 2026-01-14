<?php

class Product {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addProduct($name, $description, $image_path, $pdf_path, $created_by) {
        try {
            $id = $this->db->insert('product', [
                'name' => $name,
                'description' => $description,
                'image_path' => $image_path,
                'pdf_path' => $pdf_path,
                'created_by' => $created_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $id;
        } catch (Exception $e) {
            throw new Exception('Gabim ne shtimin e produktit: ' . $e->getMessage());
        }
    }

    public function getProduct($id) {
        return $this->db->fetch(
            "SELECT p.*, u.username as created_by_name FROM product p 
             LEFT JOIN user u ON p.created_by = u.id 
             WHERE p.id = ?",
            [$id]
        );
    }

    public function getAllProducts() {
        return $this->db->fetchAll(
            "SELECT p.*, u.username as created_by_name FROM product p 
             LEFT JOIN user u ON p.created_by = u.id 
             ORDER BY p.created_at DESC"
        );
    }

    public function getLatestProducts($limit = 6) {
        return $this->db->fetchAll(
            "SELECT p.*, u.username as created_by_name FROM product p 
             LEFT JOIN user u ON p.created_by = u.id 
             ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function updateProduct($id, $data) {
        $allowedFields = ['name', 'description', 'image_path', 'pdf_path'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->update('product', $updateData, 'id = ?', [$id]);
        }
    }

    public function deleteProduct($id) {
        $product = $this->getProduct($id);
        if ($product) {
            if ($product['image_path'] && file_exists($product['image_path'])) {
                unlink($product['image_path']);
            }
            if ($product['pdf_path'] && file_exists($product['pdf_path'])) {
                unlink($product['pdf_path']);
            }
        }
        $this->db->delete('product', 'id = ?', [$id]);
    }

    public function getProductsByUser($user_id) {
        return $this->db->fetchAll(
            "SELECT * FROM product WHERE created_by = ? ORDER BY created_at DESC",
            [$user_id]
        );
    }

    public function searchProducts($keyword) {
        return $this->db->fetchAll(
            "SELECT p.*, u.username as created_by_name FROM product p 
             LEFT JOIN user u ON p.created_by = u.id 
             WHERE p.name LIKE ? OR p.description LIKE ? 
             ORDER BY p.created_at DESC",
            ["%{$keyword}%", "%{$keyword}%"]
        );
    }
}
?>
