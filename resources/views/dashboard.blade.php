<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>

    <!-- Подключение стилей -->
    <link rel="stylesheet" href="/css/dashboard.css">

    <!-- Метатег CSRF-токена для безопасности -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Передача данных пользователя в метатег -->
    <meta name="user" content="{{ json_encode($user) }}">
</head>
<body>
<div id="app">
    <!-- Вывод компонента Dashboard -->
    <?= Dashboard($user) ?>
</div>

<!-- Подключение скриптов -->
<script src="/js/dashboard.js"></script>
</body>
</html>
