<?php
require_once '../db.php';
$pageTitle = "Сотрудники"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';

$query = "SELECT * FROM employees ORDER BY last_name ASC";
$result = $conn->query($query);
?>

<h4>Сотрудники</h4>
<div class="right-align" style="margin-bottom: 20px;">
  <a href="create" class="btn blue waves-effect"><i class="material-icons left">add</i>Добавить</a>
</div>

<table class="highlight striped responsive-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>ФИО</th>
      <th>Должность</th>
      <th>Паспорт</th>
      <th>Кем выдан</th>
      <th>Дата выдачи</th>
      <th>Действия</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars("{$row['last_name']} {$row['first_name']} {$row['middle_name']}") ?></td>
        <td><?= htmlspecialchars($row['post']) ?></td>
        <td><?= "{$row['passport_series']} {$row['passport_number']}" ?></td>
        <td><?= htmlspecialchars($row['passport_issued_by']) ?></td>
        <td><?= $row['passport_date_of_issue'] ?></td>
        <td>
          <a href="edit?id=<?= $row['id'] ?>" class="btn-small orange"><i class="material-icons">edit</i></a>
          <a href="delete?id=<?= $row['id'] ?>" class="btn-small red" onclick="return confirm('Удалить?');"><i class="material-icons">delete</i></a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../templates/layout_footer.php'; ?>
