<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = $_POST['first_name'];
  $last = $_POST['last_name'];
  $middle = $_POST['middle_name'];
  $post = $_POST['post'];
  $series = $_POST['passport_series'];
  $number = $_POST['passport_number'];
  $issued = $_POST['passport_issued_by'];
  $date = $_POST['passport_date_of_issue'];

  $stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, middle_name, post, passport_series, passport_number, passport_issued_by, passport_date_of_issue) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssss", $first, $last, $middle, $post, $series, $number, $issued, $date);
  $stmt->execute();

  header("Location: list");
  exit;
}

$pageTitle = "Сотрудники"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';
?>

<h4>Добавить сотрудника</h4>
<form method="post">
  <div class="input-field"><input name="last_name" required><label>Фамилия</label></div>
  <div class="input-field"><input name="first_name" required><label>Имя</label></div>
  <div class="input-field"><input name="middle_name"><label>Отчество</label></div>
  <div class="input-field"><input name="post" required><label>Должность</label></div>
  <div class="input-field"><input name="passport_series" required><label>Серия паспорта</label></div>
  <div class="input-field"><input name="passport_number" required><label>Номер паспорта</label></div>
  <div class="input-field"><input name="passport_issued_by" required><label>Кем выдан</label></div>
  <div class="input-field"><input type="date" name="passport_date_of_issue" required><label>Дата выдачи</label></div>
  <button class="btn green">Добавить</button>
</form>

<?php include '../templates/layout_footer.php'; ?>
