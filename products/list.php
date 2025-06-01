<?php
require_once '../db.php';
$pageTitle = "Товары"; // или "Товары", "Контрагенты" и т.д.
include '../templates/layout.php';
$result = $conn->query("SELECT * FROM products");
?>
<h4>Товары</h4>
<div class='right-align' style='margin-bottom: 20px;'>
  <a href='create' class='btn blue'><i class='material-icons left'>add</i>Добавить</a>
</div>
<table class='highlight'>
  <thead>
    <tr>
      <th>ID</th><th>Название</th><th>Цена</th><th>ID единицы измерения</th><th>Действия</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td><td><?= htmlspecialchars($row['name']) ?></td><td><?= htmlspecialchars($row['price']) ?></td><td><?= htmlspecialchars($row['unit_id']) ?></td>
        <td>
          <a href='edit?id=<?= $row['id'] ?>' class='btn-small orange'><i class='material-icons'>edit</i></a>
          <a href='delete?id=<?= $row['id'] ?>' class='btn-small red' onclick='return confirm("Удалить?");'><i class='material-icons'>delete</i></a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include '../templates/layout_footer.php'; ?>
