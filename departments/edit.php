<?php
require_once '../db.php';
$pageTitle = "Редактировать Отдел";
include '../templates/layout.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = mysqli_query($conn, "SELECT * FROM departments WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $sql = "UPDATE departments SET name='$name' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header("Location: list");
        exit();
    } else {
        echo "Ошибка: " . mysqli_error($conn);
    }
}
?>
<h4>Редактировать Отдел</h4>
<form method="post">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <div class="input-field">
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
        <label class="active" for="name">Название</label>
    </div>
    <button type="submit" class="btn orange"><i class="material-icons left">save</i>Сохранить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
