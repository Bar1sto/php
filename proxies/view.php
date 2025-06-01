<?php
require_once '../db.php';
include '../templates/layout.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем доверенность с JOIN'ами
$query = "
SELECT p.*, 
       o.name AS organization, o.address, o.chief, o.financial_chief,
       a.account, a.bank_name, a.bank_identity_number,
       c.name AS customer,
       e.first_name, e.last_name, e.middle_name, e.passport_series, 
       e.passport_number, e.passport_issued_by, e.passport_date_of_issue
FROM proxies p
JOIN organizations o ON p.organization_id = o.id
JOIN accounts a ON o.account_id = a.id
JOIN customers c ON p.customer_id = c.id
JOIN employees e ON p.employee_id = e.id
WHERE p.id = $id
LIMIT 1
";

$result = $conn->query($query);
$data = $result->fetch_assoc();

if (!$data) {
    echo "<h5>Доверенность не найдена</h5>";
    include '../templates/layout_footer';
    exit();
}

// Получаем товары для доверенности
$items = $conn->query("
SELECT pb.product_amount, pr.name AS product_name, u.name AS unit
FROM proxy_bodies pb
JOIN products pr ON pb.product_id = pr.id
JOIN units u ON pr.unit_id = u.id
WHERE pb.proxy_id = $id
");
?>

<div class="row valign-wrapper">
  <h5 class="col s10">Просмотр доверенности</h5>
  <div class="col s2 right-align not-print">
    <button class="btn-floating z-depth-0 waves-effect waves-light" onclick="printElementById('print-area')">
      <i class="material-icons green">print</i>
    </button>
  </div>
</div>

<main id="print-area" class="white print-only">
  <table class="highlight">
    <tr><td><b>Код доверенности</b></td><td><?= $data['id'] ?></td></tr>
    <tr><td><b>Организация</b></td><td><?= htmlspecialchars($data['organization']) ?></td></tr>
    <tr><td><b>Адрес</b></td><td><?= htmlspecialchars($data['address']) ?></td></tr>
    <tr><td><b>Банк</b></td>
        <td><?= htmlspecialchars($data['bank_name']) ?> | БИК: <?= $data['bank_identity_number'] ?></td></tr>
    <tr><td><b>Счёт</b></td><td><?= $data['account'] ?></td></tr>
    <tr><td><b>Генеральный директор</b></td><td><?= htmlspecialchars($data['chief']) ?></td></tr>
    <tr><td><b>Главный бухгалтер</b></td><td><?= htmlspecialchars($data['financial_chief']) ?></td></tr>
    <tr><td><b>Контрагент</b></td><td><?= htmlspecialchars($data['customer']) ?></td></tr>
    <tr><td><b>Сотрудник</b></td>
        <td><?= $data['last_name'] . ' ' . $data['first_name'] . ' ' . $data['middle_name'] ?></td></tr>
    <tr><td><b>Паспорт</b></td>
        <td>Серия: <?= $data['passport_series'] ?> № <?= $data['passport_number'] ?></td></tr>
    <tr><td><b>Кем выдан</b></td><td><?= htmlspecialchars($data['passport_issued_by']) ?></td></tr>
    <tr><td><b>Дата выдачи паспорта</b></td><td><?= $data['passport_date_of_issue'] ?></td></tr>
    <tr><td><b>Дата выдачи доверенности</b></td><td><?= $data['date_of_issue'] ?></td></tr>
    <tr><td><b>Действительна до</b></td><td><?= $data['is_valid_until'] ?></td></tr>
  </table>

  <h6 style="margin-top: 30px;">Список товаров</h6>
  <table class="highlight bordered">
    <thead>
      <tr>
        <th>№</th>
        <th>Наименование</th>
        <th>Ед. изм.</th>
        <th>Кол-во</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; while($item = $items->fetch_assoc()): ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($item['product_name']) ?></td>
          <td><?= htmlspecialchars($item['unit']) ?></td>
          <td><?= $item['product_amount'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>

<script>
  function printElementById(id) {
    var printContents = document.getElementById(id).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
  }
</script>

<?php include '../templates/layout_footer.php'; ?>
