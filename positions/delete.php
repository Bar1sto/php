<?php
require_once '../db.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM positions WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: list");
        exit();
    } else {
        echo "Ошибка: " . mysqli_error($conn);
    }
}
?>