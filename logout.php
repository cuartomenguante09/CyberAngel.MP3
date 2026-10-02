<?php
session_start();
require_once 'conexion.php';

if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET session_token = NULL WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } catch (PDOException $e) {}
}

session_unset();
session_destroy();
header("Location: login.php");
exit();
?>