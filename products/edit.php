<?php
require_once '../db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $unit_id = $_POST['unit_id'];

  $stmt = $conn->prepare("UPDATE products SET name=?, price=?, unit_id=? WHERE id = ?");
  $stmt->bind_param("sssi", $name, $price, $unit_id, $id);
  $stmt->execute();
  header("Location: list");
  exit;
}

$pageTitle = "Товары"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';
?>
<h4>Редактировать товар</h4>
<form method="post">
<div class="input-field"><input name="name" value="<?= htmlspecialchars($row['name']) ?>" type="text" required><label class="active">Название</label></div>
<div class="input-field"><input name="price" value="<?= htmlspecialchars($row['price']) ?>" type="number" required><label class="active">Цена</label></div>
<div class="input-field"><input name="unit_id" value="<?= htmlspecialchars($row['unit_id']) ?>" type="number" required><label class="active">ID единицы измерения</label></div>
  <button class="btn blue">Сохранить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
