<?php
require_once '../db.php';
include '../templates/layout.php';

// Получаем данные для select-ов
$organizations = $conn->query("SELECT id, name FROM organizations ORDER BY name ASC");
$employees = $conn->query("
    SELECT id, CONCAT(last_name, ' ', first_name, ' ', IFNULL(middle_name, '')) AS fullname 
    FROM employees ORDER BY last_name
");
$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");
$products = $conn->query("
    SELECT p.id, p.name, u.name AS unit 
    FROM products p
    JOIN units u ON p.unit_id = u.id
");

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $org_id = $_POST['organization_id'];
    $emp_id = $_POST['employee_id'];
    $cust_id = $_POST['customer_id'];
    $date_from = $_POST['date_of_issue'];
    $date_to = $_POST['is_valid_until'];

    $stmt = $conn->prepare("INSERT INTO proxies 
        (organization_id, customer_id, employee_id, date_of_issue, is_valid_until) 
        VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $org_id, $cust_id, $emp_id, $date_from, $date_to);
    $stmt->execute();

    $proxy_id = $conn->insert_id;

    // Обрабатываем товары
    if (!empty($_POST['products'])) {
        $stmt = $conn->prepare("INSERT INTO proxy_bodies (proxy_id, product_id, product_amount) VALUES (?, ?, ?)");
        foreach ($_POST['products'] as $row) {
            $pid = intval($row['id']);
            $amt = intval($row['amount']);
            $stmt->bind_param("iii", $proxy_id, $pid, $amt);
            $stmt->execute();
        }
    }

    header("Location: list");
    exit();
}
?>

<h4>Создание доверенности</h4>

<form method="post" class="col s12">
  <div class="row">
    <div class="input-field col s6">
      <select name="organization_id" required>
        <option value="" disabled selected>Выберите организацию</option>
        <?php while($row = $organizations->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <label>Организация</label>
    </div>

    <div class="input-field col s6">
      <select name="employee_id" required>
        <option value="" disabled selected>Выберите сотрудника</option>
        <?php while($row = $employees->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['fullname']) ?></option>
        <?php endwhile; ?>
      </select>
      <label>Сотрудник</label>
    </div>
  </div>

  <div class="row">
    <div class="input-field col s6">
      <select name="customer_id" required>
        <option value="" disabled selected>Выберите контрагента</option>
        <?php while($row = $customers->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
      </select>
      <label>Контрагент</label>
    </div>

    <div class="input-field col s3">
      <input type="date" name="date_of_issue" required>
      <label class="active">Дата выдачи</label>
    </div>

    <div class="input-field col s3">
      <input type="date" name="is_valid_until" required>
      <label class="active">Действительно до</label>
    </div>
  </div>

  <h5>Товары в доверенности</h5>

  <div class="row">
    <div class="input-field col s6">
      <select id="product-select">
        <option value="" disabled selected>Выберите товар</option>
        <?php while($row = $products->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>" data-unit="<?= htmlspecialchars($row['unit']) ?>">
            <?= htmlspecialchars($row['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <label>Товар</label>
    </div>
    <div class="input-field col s3">
      <input type="number" id="product-amount" min="1" value="1">
      <label class="active">Количество</label>
    </div>
    <div class="col s3" style="margin-top: 20px;">
      <button type="button" class="btn green" onclick="addProductRow()">
        <i class="material-icons left">add</i>Добавить
      </button>
    </div>
  </div>

  <table class="highlight" id="product-table">
    <thead>
      <tr>
        <th>Товар</th>
        <th>Ед. изм.</th>
        <th>Кол-во</th>
        <th>Удалить</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <div id="product-inputs"></div>

  <button class="btn waves-effect blue" type="submit">
    <i class="material-icons left">done</i>Создать
  </button>
</form>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    M.FormSelect.init(document.querySelectorAll('select'));
  });

  let rowCount = 0;

  function addProductRow() {
    const select = document.getElementById('product-select');
    const amount = document.getElementById('product-amount').value;
    const productId = select.value;
    const productName = select.options[select.selectedIndex].text;
    const unit = select.options[select.selectedIndex].getAttribute('data-unit');

    if (!productId || amount <= 0) {
      alert('Выберите товар и введите количество');
      return;
    }

    const tbody = document.getElementById('product-table').querySelector('tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${productName}</td>
      <td>${unit}</td>
      <td>${amount}</td>
      <td><button type="button" class="btn red btn-small" onclick="this.parentNode.parentNode.remove()">
        <i class="material-icons">delete</i>
      </button></td>
    `;
    tbody.appendChild(row);

    const hidden = document.getElementById('product-inputs');
    hidden.innerHTML += `
      <input type="hidden" name="products[${rowCount}][id]" value="${productId}">
      <input type="hidden" name="products[${rowCount}][amount]" value="${amount}">
    `;
    rowCount++;

    select.selectedIndex = 0;
    M.FormSelect.init(select);
    document.getElementById('product-amount').value = 1;
  }
</script>

<?php include '../templates/layout_footer.php'; ?>
