<?php
require_once '../db.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  try {
    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: list");
    exit;

  } catch (mysqli_sql_exception $e) {
    echo "<script>alert('Невозможно удалить: контрагент используется в других документах.'); window.location.href='list.php';</script>";
    exit;
  }
}
