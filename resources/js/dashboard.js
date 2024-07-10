import '../css/dashboard/dashboard.css';
import Dashboard from './components/Dashboard';

const userMeta = document.querySelector('meta[name="user"]');
const user = userMeta ? JSON.parse(userMeta.getAttribute('content')) : { isAuthenticated: false };

document.querySelector('#app').innerHTML = Dashboard(user);

function loadChannels() {
    fetch('/get-channels', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Полученные каналы:', data); // Добавлено для отладки
            const channelList = document.getElementById('channel-list');
            channelList.innerHTML = '';
            data.forEach(channel => {
                const li = document.createElement('li');
                const button = document.createElement('button');
                const avatar = channel.avatar ? channel.avatar : '/images/default-avatar.webp';

                button.innerHTML = `
                    <img src="${avatar}" alt="${channel.title}" class="channel-avatar">
                    <span>${channel.title}</span>
                `;
                button.classList.add('channel-button');
                button.addEventListener('click', () => {
                    displayChannelFunctionality(channel);
                });
                li.appendChild(button);
                channelList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки каналов:', error);
        });
}

function displayChannelFunctionality(channel) {
    const functionalityDiv = document.getElementById('channel-functionality');
    functionalityDiv.innerHTML = `
        <h2>Управление каналом: @${channel.username}</h2>
        <p>Здесь будет функционал для управления каналом.</p>
        <!-- Добавьте сюда нужные элементы для управления каналом -->
    `;
}

document.addEventListener('DOMContentLoaded', (event) => {
    const backButton = document.getElementById('back-to-home');
    if (backButton) {
        backButton.addEventListener('click', () => {
            window.location.href = '/';
        });
    }
});

loadChannels();
