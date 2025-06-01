<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Система документов</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
  <link href="/GoProject/static/css/styles.css" rel="stylesheet">
  <style>
  body {
  margin: 0;
  padding: 0;
}

ul.sidenav {
  margin: 0;
  padding-top: 0;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
}

.sidenav .logo-container {
  margin-top: 0;
  padding: 8px 16px;
  font-weight: bold;
  font-size: 18px;
  color: #0d47a1;
  display: flex;
  align-items: center;
}

.sidenav li:not(.logo-container) {
  border-bottom: 1px solid black;
}

.sidenav li a {
  padding-left: 24px;
}

</style>

</head>

<body class="grey lighten-4 has-fixed-sidenav">

  <!-- Боковая панель -->
  <ul class="sidenav sidenav-fixed">
    <li class="logo-container">
      <i class="material-icons left">description</i>
      <?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Документы' ?>
    </li>
    <li><a href="/GoProject/proxies/list" class="blue-text">Доверенности</a></li>
    <li><a href="/GoProject/reports/list" class="blue-text">Авансовые отчёты</a></li>
  </ul>

  <!-- Верхняя панель -->
  <div class="navbar-fixed">
    <nav class="blue navbar">
      <div class="nav-wrapper">
        <ul class="left hide-on-med-and-down">
          <li><a href="/GoProject/proxies/list">Документы</a></li>
          <li><a href="/GoProject/products/list">Товары</a></li>
          <li><a href="/GoProject/employees/list">Сотрудники</a></li>
          <li><a href="/GoProject/customers/list">Контрагенты</a></li>
          <li><a href="/GoProject/organizations/list">Организации</a></li>
          <li><a href="/GoProject/accounts/list">Счета</a></li>
          <li><a href="/GoProject/departments/list">Отделы</a></li>
          <li><a href="/GoProject/positions/list">Должности</a></li>
        </ul>
      </div>
    </nav>
  </div>

  <!-- Контент -->
  <main class="section">
    <div class="container">
