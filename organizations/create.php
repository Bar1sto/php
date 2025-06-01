<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $address = $_POST['address'];
  $account_id = $_POST['account_id'];
  $chief = $_POST['chief'];
  $financial_chief = $_POST['financial_chief'];

  $stmt = $conn->prepare("INSERT INTO organizations (name, address, account_id, chief, financial_chief) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $address, $account_id, $chief, $financial_chief);
  $stmt->execute();
  header("Location: list");
  exit;
}
$pageTitle = "Организации";
include '../templates/layout';
?>
<h4>Добавить организация</h4>
<form method="post">
<div class="input-field"><input name="name" type="text" required><label>Название</label></div>
<div class="input-field"><input name="address" type="text" required><label>Адрес</label></div>
<div class="input-field"><input name="account_id" type="number" required><label>ID счёта</label></div>
<div class="input-field"><input name="chief" type="text" required><label>Руководитель</label></div>
<div class="input-field"><input name="financial_chief" type="text" required><label>Главный бухгалтер</label></div>
  <button class="btn green">Добавить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
