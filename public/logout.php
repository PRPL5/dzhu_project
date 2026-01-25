<?php
session_start();
require_once '../config/db.php';
require_once '../src/Auth.php';
require_once '../src/User.php';

$auth = new Auth(new User($pdo));
$auth->logout();
header('Location: ../index.php');
exit;
?>
