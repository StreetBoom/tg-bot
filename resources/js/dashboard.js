import '../css/dashboard.css';
import Dashboard from './components/Dashboard';

// Получение данных пользователя из метатегов, установленных в шаблоне Blade
const userMeta = document.querySelector('meta[name="user"]');
const user = userMeta ? JSON.parse(userMeta.getAttribute('content')) : { isAuthenticated: false };

document.querySelector('#app').innerHTML = Dashboard(user);
