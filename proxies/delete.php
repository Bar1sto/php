<?php
require_once '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Удаление доверенности
    $stmt = $conn->prepare("DELETE FROM proxies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: list");
exit();
?>