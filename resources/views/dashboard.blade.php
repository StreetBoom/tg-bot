<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    @vite(['resources/css/dashboard/dashboard.css', 'resources/js/dashboard.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ json_encode($user) }}">
</head>
<body>
<div id="app"></div>
</body>
</html>
