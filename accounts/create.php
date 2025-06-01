<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $account = $_POST['account'];
  $bank_name = $_POST['bank_name'];
  $bank_identity_number = $_POST['bank_identity_number'];

  $stmt = $conn->prepare("INSERT INTO accounts (account, bank_name, bank_identity_number) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $account, $bank_name, $bank_identity_number);
  $stmt->execute();
  header("Location: list");
  exit;
}
$pageTitle = "Счета";
include '../templates/layout.php';
?>
<h4>Добавить счёт</h4>

<form method="post">
<div class="input-field"><input name="account" type="text" required><label>Номер счёта</label></div>
<div class="input-field"><input name="bank_name" type="text" required><label>Банк</label></div>
<div class="input-field"><input name="bank_identity_number" type="text" required><label>БИК</label></div>
  <button class="btn green">Добавить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
