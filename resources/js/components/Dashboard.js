export default function Dashboard(user) {
    // Функция для загрузки каналов
    function loadChannels() {
        fetch('/get-channels')
            .then(response => response.json())
            .then(data => {
                const channelList = document.getElementById('channel-list');
                channelList.innerHTML = '';
                data.forEach(channel => {
                    const li = document.createElement('li');
                    li.textContent = `${channel.channel_name} (ID: ${channel.channel_id})`;
                    channelList.appendChild(li);
                });
            });
    }

    // Вызов функции для загрузки каналов
    loadChannels();

    // Возвращаемая разметка компонента Dashboard
    return `
        <div class="dashboard">
          <aside class="sidebar">
            <div class="user-info">
              <img src="${user.avatar}" alt="${user.name}" class="avatar">
              <span class="user-name">${user.name}</span>
            </div>
            <nav class="menu">
              <ul>
                <li><a href="/profile">Профиль</a></li>
                <li><a href="/settings">Настройки</a></li>
                <li><a href="/logout">Выйти</a></li>
              </ul>
            </nav>
          </aside>
          <main class="content">
            <h1>Добро пожаловать, ${user.name}!</h1>
            <p>Это ваша личная страница.</p>
            <div>
              <h2>Ваши каналы</h2>
              <ul id="channel-list"></ul>
            </div>
          </main>
        </div>
    `;
}
