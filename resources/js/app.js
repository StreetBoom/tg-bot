import '../css/app.css';
import Navbar from './components/Navbar';
import Hero from './components/Hero';
import Features from './components/Features';
import About from './components/About';
import Footer from './components/Footer';

// Получение данных пользователя и имени бота из метатегов, установленных в шаблоне Blade
const userMeta = document.querySelector('meta[name="user"]');
const botNameMeta = document.querySelector('meta[name="bot-name"]');
const user = userMeta ? JSON.parse(userMeta.getAttribute('content')) : { isAuthenticated: false };
const botName = botNameMeta ? botNameMeta.getAttribute('content') : 'your_telegram_bot';

document.querySelector('#app').innerHTML = `
  ${Navbar(user, botName)}
  ${Hero()}
  ${Features()}
  ${About()}
  ${Footer()}
`;

// Добавляем логику для модального окна
document.addEventListener('DOMContentLoaded', (event) => {
    // Получаем элементы модального окна
    const modal = document.getElementById("login-modal");
    const btn = document.getElementById("login-btn");
    const span = document.getElementsByClassName("close")[0];

    // Когда пользователь нажимает на кнопку, открывается модальное окно
    if (btn) {
        btn.onclick = function(event) {
            event.preventDefault(); // Предотвращает переход по ссылке
            modal.style.display = "block";
        }
    }

    // Когда пользователь нажимает на <span> (x), закрывается модальное окно
    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    // Когда пользователь нажимает в любом месте вне модального окна, оно закрывается
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Логика для выхода из системы
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.onclick = function(event) {
            event.preventDefault();
            fetch('/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = '/';
                } else {
                    alert('Ошибка при выходе из системы');
                }
            }).catch(error => {
                console.error('Ошибка:', error);
            });
        };
    }
});
