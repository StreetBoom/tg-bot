import '../css/dashboard.css';
import Dashboard from './components/Dashboard';

// Получение данных пользователя из метатегов, установленных в шаблоне Blade
const userMeta = document.querySelector('meta[name="user"]');
const user = userMeta ? JSON.parse(userMeta.getAttribute('content')) : { isAuthenticated: false };

document.querySelector('#app').innerHTML = Dashboard(user);

// Функция для загрузки каналов
function loadChannels() {
    fetch('/get-channels')
        .then(response => response.json())
        .then(data => {
            console.log('Полученные каналы:', data); // Добавлено для отладки
            const channelList = document.getElementById('channel-list');
            channelList.innerHTML = '';
            data.forEach(channel => {
                const li = document.createElement('li');
                li.textContent = `${channel.channel_name} (ID: ${channel.channel_id})`;
                channelList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки каналов:', error);
        });
}

// Загрузка каналов при загрузке страницы
loadChannels();
