<?php
require_once '../db.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  try {
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: list");
    exit;

  } catch (mysqli_sql_exception $e) {
    // Ошибка удаления (например, из-за внешнего ключа)
    echo "<script>alert('Невозможно удалить сотрудника: он используется в других документах.'); window.location.href='list';</script>";
    exit;
  }
}
