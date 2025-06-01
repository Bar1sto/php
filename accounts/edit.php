<?php
require_once '../db.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM accounts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $account = $_POST['account'];
  $bank_name = $_POST['bank_name'];
  $bank_identity_number = $_POST['bank_identity_number'];

  $stmt = $conn->prepare("UPDATE accounts SET account=?, bank_name=?, bank_identity_number=? WHERE id = ?");
  $stmt->bind_param("sssi", $account, $bank_name, $bank_identity_number, $id);
  $stmt->execute();
  header("Location: list");
  exit;
}
$pageTitle = "Счета";
include '../templates/layout.php';
?>
<h4>Редактировать счёт</h4>

<form method="post">
<div class="input-field"><input name="account" value="<?= htmlspecialchars($row['account']) ?>" type="text" required><label class="active">Номер счёта</label></div>
<div class="input-field"><input name="bank_name" value="<?= htmlspecialchars($row['bank_name']) ?>" type="text" required><label class="active">Банк</label></div>
<div class="input-field"><input name="bank_identity_number" value="<?= htmlspecialchars($row['bank_identity_number']) ?>" type="text" required><label class="active">БИК</label></div>
  <button class="btn blue">Сохранить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
