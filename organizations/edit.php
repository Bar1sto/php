<?php
require_once '../db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM organizations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $address = $_POST['address'];
  $account_id = $_POST['account_id'];
  $chief = $_POST['chief'];
  $financial_chief = $_POST['financial_chief'];

  $stmt = $conn->prepare("UPDATE organizations SET name=?, address=?, account_id=?, chief=?, financial_chief=? WHERE id = ?");
  $stmt->bind_param("sssssi", $name, $address, $account_id, $chief, $financial_chief, $id);
  $stmt->execute();
  header("Location: list");
  exit;
}
$pageTitle = "Организации";
include '../templates/layout.php';
?>
<h4>Редактировать организация</h4>
<form method="post">
<div class="input-field"><input name="name" value="<?= htmlspecialchars($row['name']) ?>" type="text" required><label class="active">Название</label></div>
<div class="input-field"><input name="address" value="<?= htmlspecialchars($row['address']) ?>" type="text" required><label class="active">Адрес</label></div>
<div class="input-field"><input name="account_id" value="<?= htmlspecialchars($row['account_id']) ?>" type="number" required><label class="active">ID счёта</label></div>
<div class="input-field"><input name="chief" value="<?= htmlspecialchars($row['chief']) ?>" type="text" required><label class="active">Руководитель</label></div>
<div class="input-field"><input name="financial_chief" value="<?= htmlspecialchars($row['financial_chief']) ?>" type="text" required><label class="active">Главный бухгалтер</label></div>
  <button class="btn blue">Сохранить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
