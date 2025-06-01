<?php
require_once '../db.php';
$pageTitle = "Организации";
include '../templates/layout.php';

$result = $conn->query("SELECT * FROM organizations");
?>
<h4>Организации</h4>
<div class='right-align' style='margin-bottom: 20px;'>
  <a href='create' class='btn blue'><i class='material-icons left'>add</i>Добавить</a>
</div>
<table class='highlight'>
  <thead>
    <tr>
      <th>ID</th><th>Название</th><th>Адрес</th><th>ID счёта</th><th>Руководитель</th><th>Главный бухгалтер</th><th>Действия</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td><td><?= htmlspecialchars($row['name']) ?></td><td><?= htmlspecialchars($row['address']) ?></td><td><?= htmlspecialchars($row['account_id']) ?></td><td><?= htmlspecialchars($row['chief']) ?></td><td><?= htmlspecialchars($row['financial_chief']) ?></td>
        <td>
          <a href='edit?id=<?= $row['id'] ?>' class='btn-small orange'><i class='material-icons'>edit</i></a>
          <a href='delete?id=<?= $row['id'] ?>' class='btn-small red' onclick='return confirm("Удалить?");'><i class='material-icons'>delete</i></a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include '../templates/layout_footer.php'; ?>
