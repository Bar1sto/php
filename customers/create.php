<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  $stmt = $conn->prepare("INSERT INTO customers (name) VALUES (?)");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  header("Location: list");
  exit;
}
$pageTitle = "Контрагенты";
include '../templates/layout.php';
?>
<h4>Добавить контрагент</h4>
<form method="post">
<div class="input-field"><input name="name" type="text" required><label>Название</label></div>
  <button class="btn green">Добавить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
