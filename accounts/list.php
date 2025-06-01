<?php
require_once '../db.php';
$pageTitle = "Счета"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';
$result = $conn->query("SELECT * FROM accounts");
?>
<h4>Счета</h4>

<div class='right-align' style='margin-bottom: 20px;'>
  <a href='create' class='btn blue'><i class='material-icons left'>add</i>Добавить</a>
</div>
<table class='highlight'>
  <thead>
    <tr>
      <th>ID</th><th>Номер счёта</th><th>Банк</th><th>БИК</th><th>Действия</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td><td><?= htmlspecialchars($row['account']) ?></td><td><?= htmlspecialchars($row['bank_name']) ?></td><td><?= htmlspecialchars($row['bank_identity_number']) ?></td>
        <td>
          <a href='edit?id=<?= $row['id'] ?>' class='btn-small orange'><i class='material-icons'>edit</i></a>
          <a href='delete?id=<?= $row['id'] ?>' class='btn-small red' onclick='return confirm("Удалить?");'><i class='material-icons'>delete</i></a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include '../templates/layout_footer.php'; ?>
