<?php
require_once '../db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  $stmt = $conn->prepare("UPDATE customers SET name=? WHERE id = ?");
  $stmt->bind_param("si", $name, $id);
  $stmt->execute();
  header("Location: list");
  exit;
}
$pageTitle = "Контрагенты";
include '../templates/layout.php';
?>
<h4>Редактировать контрагент</h4>
<form method="post">
<div class="input-field"><input name="name" value="<?= htmlspecialchars($row['name']) ?>" type="text" required><label class="active">Название</label></div>
  <button class="btn blue">Сохранить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
