<?php

class Database {
    private $pdo;
    private $host;
    private $dbname;
    private $user;
    private $pass;

    public function __construct($host = 'localhost', $dbname = 'dzhu_db', $user = 'root', $pass = '') {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;
        $this->connect();
    }

    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->pdo = new PDO(
                $dsn,
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die('Gabim në lidhjen me databazën: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception('Gabim në ekzekutimin e query: ' . $e->getMessage());
        }
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetch($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch();
    }

    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->execute($sql, array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $condition, $params = []) {
        $set = implode(',', array_map(function($col) { return "$col=?"; }, array_keys($data)));
        $sql = "UPDATE {$table} SET {$set} WHERE {$condition}";
        $this->execute($sql, array_merge(array_values($data), $params));
    }

    public function delete($table, $condition, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$condition}";
        $this->execute($sql, $params);
    }
}
?>
