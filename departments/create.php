<?php
require_once '../db.php';
$pageTitle = "Добавить Отдел";
include '../templates/layout.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $sql = "INSERT INTO departments (name) VALUES ('$name')";
    if (mysqli_query($conn, $sql)) {
        header("Location: list");
        exit();
    } else {
        echo "Ошибка: " . mysqli_error($conn);
    }
}
?>
<h4>Добавить Отдел</h4>
<form method="post">
    <div class="input-field">
        <input type="text" id="name" name="name" required>
        <label for="name">Название</label>
    </div>
    <button type="submit" class="btn blue"><i class="material-icons left">save</i>Сохранить</button>
</form>
<?php include '../templates/layout_footer.php'; ?>
