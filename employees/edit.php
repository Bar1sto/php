<?php
require_once '../db.php';
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = $_POST['first_name'];
  $last = $_POST['last_name'];
  $middle = $_POST['middle_name'];
  $post = $_POST['post'];
  $series = $_POST['passport_series'];
  $number = $_POST['passport_number'];
  $issued = $_POST['passport_issued_by'];
  $date = $_POST['passport_date_of_issue'];

  $stmt = $conn->prepare("UPDATE employees SET first_name=?, last_name=?, middle_name=?, post=?, passport_series=?, passport_number=?, passport_issued_by=?, passport_date_of_issue=? WHERE id=?");
  $stmt->bind_param("ssssssssi", $first, $last, $middle, $post, $series, $number, $issued, $date, $id);
  $stmt->execute();

  header("Location: list");
  exit;
}

$pageTitle = "Сотрудники"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';
?>

<h4>Редактировать сотрудника</h4>
<form method="post">
  <div class="input-field"><input name="last_name" value="<?= $employee['last_name'] ?>" required><label class="active">Фамилия</label></div>
  <div class="input-field"><input name="first_name" value="<?= $employee['first_name'] ?>" required><label class="active">Имя</label></div>
  <div class="input-field"><input name="middle_name" value="<?= $employee['middle_name'] ?>"><label class="active">Отчество</label></div>
  <div class="input-field"><input name="post" value="<?= $employee['post'] ?>" required><label class="active">Должность</label></div>
  <div class="input-field"><input name="passport_series" value="<?= $employee['passport_series'] ?>" required><label class="active">Серия паспорта</label></div>
  <div class="input-field"><input name="passport_number" value="<?= $employee['passport_number'] ?>" required><label class="active">Номер паспорта</label></div>
  <div class="input-field"><input name="passport_issued_by" value="<?= $employee['passport_issued_by'] ?>" required><label class="active">Кем выдан</label></div>
  <div class="input-field"><input type="date" name="passport_date_of_issue" value="<?= $employee['passport_date_of_issue'] ?>" required><label class="active">Дата выдачи</label></div>
  <button class="btn blue">Сохранить</button>
</form>

<?php include '../templates/layout_footer.php'; ?>
