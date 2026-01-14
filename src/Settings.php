<?php

class Settings {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getSetting($key) {
        $result = $this->db->fetch(
            "SELECT value FROM config WHERE `key` = ?",
            [$key]
        );
        return $result ? $result['value'] : null;
    }

    public function setSetting($key, $value) {
        try {
            $existing = $this->db->fetch(
                "SELECT id FROM config WHERE `key` = ?",
                [$key]
            );

            if ($existing) {
                $this->db->update('config', ['value' => $value], '`key` = ?', [$key]);
            } else {
                $this->db->insert('config', [
                    '`key`' => $key,
                    'value' => $value
                ]);
            }
        } catch (Exception $e) {
            throw new Exception('Gabim ne ruajtjen e setting-ut: ' . $e->getMessage());
        }
    }

    public function updateSetting($key, $value) {
        $this->setSetting($key, $value);
    }

    public function getAllSettings() {
        return $this->db->fetchAll(
            "SELECT * FROM config ORDER BY `key` ASC"
        );
    }

    public function deleteSetting($key) {
        $this->db->delete('config', '`key` = ?', [$key]);
    }

    public function getAboutText() {
        return $this->getSetting('about_text');
    }

    public function setAboutText($text) {
        $this->setSetting('about_text', $text);
    }

    public function getHomeText() {
        return $this->getSetting('home_text');
    }

    public function setHomeText($text) {
        $this->setSetting('home_text', $text);
    }

    public function getCompanyName() {
        return $this->getSetting('company_name') ?? 'DZHU';
    }

    public function setCompanyName($name) {
        $this->setSetting('company_name', $name);
    }

    public function getCompanyEmail() {
        return $this->getSetting('company_email') ?? 'info@dzhu.com';
    }

    public function setCompanyEmail($email) {
        $this->setSetting('company_email', $email);
    }

    public function getCompanyPhone() {
        return $this->getSetting('company_phone') ?? '+383 1 234 567';
    }

    public function setCompanyPhone($phone) {
        $this->setSetting('company_phone', $phone);
    }
}
?>
