<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('path/to/your/image.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.5rem;
            margin-bottom: 40px;
        }
        .navbar {
            margin-bottom: 50px;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .content-section {
            padding: 60px 0;
        }
        .content-section h2 {
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
        }
        .feature-card {
            background: white;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-card i {
            color: #007bff;
            margin-bottom: 20px;
        }
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .feature-card p {
            font-size: 1rem;
        }
        .cta {
            text-align: center;
            margin-top: 40px;
        }
        .cta a {
            font-size: 1.25rem;
            padding: 15px 30px;
        }
        footer {
            background: #f8f9fa;
            padding: 20px 0;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<!-- Навигационная панель -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Telegram Bot</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Функционал</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">О боте</a>
                </li>
                <li class="nav-item">
                    @if(auth()->user())
                        <a class="nav-link btn btn-primary text-white" href="#"><img height="100" src="{{asset(auth()->user()->avatar)}} " alt="">
                            {{auth()->user()->name}}</a>

                    @else
                        <a class="nav-link btn btn-primary text-white" href="#">Авторизоваться</a>

                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Герой секция -->
<div class="hero">
    <div class="container">
        <h1>Добро пожаловать!</h1>
        <p>Наш Телеграм бот поможет вам управлять задачами и получать уведомления в реальном времени.</p>
        <a class="btn btn-primary btn-lg" href="#about">Узнать больше</a>
    </div>
</div>

<!-- Описание функционала -->
<div class="content-section bg-light" id="features">
    <div class="container">
        <h2>Функционал нашего бота</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-tasks fa-3x"></i>
                    <h3>Управление задачами</h3>
                    <p>Легко создавайте, обновляйте и удаляйте задачи прямо из Телеграм.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-bell fa-3x"></i>
                    <h3>Уведомления</h3>
                    <p>Получайте важные уведомления и напоминания в режиме реального времени.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-chart-line fa-3x"></i>
                    <h3>Аналитика</h3>
                    <p>Отслеживайте прогресс и анализируйте свои задачи с помощью встроенной аналитики.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Описание бота -->
<div class="content-section" id="about">
    <div class="container">
        <h2>Зачем нужен наш бот?</h2>
        <p class="text-center">Наш Телеграм бот предназначен для того, чтобы упростить вашу жизнь, предоставляя удобный инструмент для управления задачами и получения уведомлений. С ним вы всегда будете в курсе своих дел и сможете эффективно планировать свое время.</p>
        <div class="cta">
            <a class="btn btn-secondary btn-lg" href="/register">Регистрация</a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <p>© 2024 Telegram Bot. Все права защищены.</p>
    </div>
</footer>

<!-- Подключение скриптов -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
