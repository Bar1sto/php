<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $unit_id = $_POST['unit_id'];

  $stmt = $conn->prepare("INSERT INTO products (name, price, unit_id) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $price, $unit_id);
  $stmt->execute();
  header("Location: list");
  exit;
}

$pageTitle = "Товары"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';
?>
<h4>Добавить товар</h4>
<form method="post">
<div class="input-field"><input name="name" type="text" required><label>Название</label></div>
<div class="input-field"><input name="price" type="number" required><label>Цена</label></div>
<div class="input-field"><input name="unit_id" type="number" required><label>ID единицы измерения</label></div>
  <button class="btn green">Добавить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
