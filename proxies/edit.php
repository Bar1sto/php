
<?php
require_once '../db.php';
include '../templates/layout.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM proxies WHERE id = $id";
$proxy = $conn->query($query)->fetch_assoc();

if (!$proxy) {
    echo "<h5>Доверенность не найдена</h5>";
    include '../templates/layout_footer';
    exit();
}

$proxy_items = $conn->query("
SELECT pb.product_id, pb.product_amount, pr.name, u.name AS unit
FROM proxy_bodies pb
JOIN products pr ON pb.product_id = pr.id
JOIN units u ON pr.unit_id = u.id
WHERE proxy_id = $id
");

$organizations = $conn->query("SELECT id, name FROM organizations ORDER BY name ASC");
$employees = $conn->query("SELECT id, CONCAT(last_name, ' ', first_name, ' ', IFNULL(middle_name, '')) AS fullname FROM employees ORDER BY last_name");
$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");
$products = $conn->query("SELECT p.id, p.name, u.name AS unit FROM products p JOIN units u ON p.unit_id = u.id");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $org_id = $_POST['organization_id'];
    $emp_id = $_POST['employee_id'];
    $cust_id = $_POST['customer_id'];
    $date_from = $_POST['date_of_issue'];
    $date_to = $_POST['is_valid_until'];

    $stmt = $conn->prepare("UPDATE proxies SET organization_id=?, customer_id=?, employee_id=?, date_of_issue=?, is_valid_until=? WHERE id=?");
    $stmt->bind_param("iiissi", $org_id, $cust_id, $emp_id, $date_from, $date_to, $id);
    $stmt->execute();

    $conn->query("DELETE FROM proxy_bodies WHERE proxy_id = $id");

    if (!empty($_POST['products'])) {
        $stmt = $conn->prepare("INSERT INTO proxy_bodies (proxy_id, product_id, product_amount) VALUES (?, ?, ?)");
        foreach ($_POST['products'] as $row) {
            $pid = intval($row['id']);
            $amt = intval($row['amount']);
            $stmt->bind_param("iii", $id, $pid, $amt);
            $stmt->execute();
        }
    }

    header("Location: list");
    exit();
}
?>

<h4>Редактирование доверенности #<?= $id ?></h4>

<form method="post" class="col s12">
  <!-- Шапка доверенности -->
  <div class="row">
    <div class="input-field col s6">
      <select name="organization_id" required>
        <option value="" disabled>Выберите организацию</option>
        <?php while($row = $organizations->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>" <?= $row['id'] == $proxy['organization_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <label>Организация</label>
    </div>

    <div class="input-field col s6">
      <select name="employee_id" required>
        <option value="" disabled>Выберите сотрудника</option>
        <?php while($row = $employees->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>" <?= $row['id'] == $proxy['employee_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['fullname']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <label>Сотрудник</label>
    </div>
  </div>

  <div class="row">
    <div class="input-field col s6">
      <select name="customer_id" required>
        <option value="" disabled>Выберите контрагента</option>
        <?php while($row = $customers->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>" <?= $row['id'] == $proxy['customer_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <label>Контрагент</label>
    </div>

    <div class="input-field col s3">
      <input type="date" name="date_of_issue" value="<?= $proxy['date_of_issue'] ?>" required>
      <label class="active">Дата выдачи</label>
    </div>

    <div class="input-field col s3">
      <input type="date" name="is_valid_until" value="<?= $proxy['is_valid_until'] ?>" required>
      <label class="active">Действительно до</label>
    </div>
  </div>

  <h5>Товары</h5>

  <div class="row">
    <div class="input-field col s6">
      <select id="product-select">
        <option value="" disabled selected>Выберите товар</option>
        <?php mysqli_data_seek($products, 0); while($row = $products->fetch_assoc()): ?>
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
    <tbody>
      <?php $i = 0; while($row = $proxy_items->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['unit']) ?></td>
          <td><?= $row['product_amount'] ?></td>
          <td><button type="button" class="btn red btn-small" onclick="removeProductRow(this, <?= $i ?>)">
            <i class="material-icons">delete</i>
          </button></td>
        </tr>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            const hidden = document.getElementById('product-inputs');
            hidden.innerHTML += `
              <input type="hidden" name="products[<?= $i ?>][id]" value="<?= $row['product_id'] ?>">
              <input type="hidden" name="products[<?= $i ?>][amount]" value="<?= $row['product_amount'] ?>">
            `;
          });
        </script>
      <?php $i++; endwhile; ?>
    </tbody>
  </table>

  <div id="product-inputs"></div>

  <button class="btn waves-effect blue" type="submit">
    <i class="material-icons left">save</i>Сохранить
  </button>
</form>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    M.FormSelect.init(document.querySelectorAll('select'));
  });

  let rowCount = <?= $i ?>;

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
      <td><button type="button" class="btn red btn-small" onclick="removeProductRow(this, ${rowCount})">
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

  function removeProductRow(button, index) {
    button.closest('tr').remove();
    const inputs = document.querySelectorAll('#product-inputs input[name^="products[' + index + ']"]');
    inputs.forEach(el => el.remove());
  }
</script>

<?php include '../templates/layout_footer.php'; ?>
