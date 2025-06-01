<?php
require_once '../db.php';
include '../templates/layout.php';

$query = "
SELECT p.id, p.date_of_issue, p.is_valid_until,
       o.name AS organization,
       c.name AS customer,
       CONCAT(e.last_name, ' ', e.first_name, ' ', e.middle_name) AS employee
FROM proxies p
JOIN organizations o ON p.organization_id = o.id
JOIN customers c ON p.customer_id = c.id
JOIN employees e ON p.employee_id = e.id
ORDER BY p.date_of_issue DESC
";

$result = $conn->query($query);
?>

<h4>Журнал Доверенностей</h4>
<div class="right-align" style="margin-bottom: 20px;">
  <a href="create" class="btn waves-effect blue"><i class="material-icons left">add</i>Добавить</a>
</div>

<table class="highlight striped responsive-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Организация</th>
      <th>Кому выдана</th>
      <th>От кого</th>
      <th>Дата выдачи</th>
      <th>Действует до</th>
      <th>Действия</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['organization']) ?></td>
        <td><?= htmlspecialchars($row['employee']) ?></td>
        <td><?= htmlspecialchars($row['customer']) ?></td>
        <td><?= $row['date_of_issue'] ?></td>
        <td><?= $row['is_valid_until'] ?></td>
        <td>
          <a href="view?id=<?= $row['id'] ?>" class="btn-small green"><i class="material-icons">visibility</i></a>
          <a href="edit?id=<?= $row['id'] ?>" class="btn-small orange"><i class="material-icons">edit</i></a>
          <a href="delete?id=<?= $row['id'] ?>" class="btn-small red" onclick="return confirm('Удалить?');"><i class="material-icons">delete</i></a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../templates/layout_footer.php'; ?>
