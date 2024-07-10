export default function Dashboard(user) {
    const defaultAvatar = '/images/default-avatar.webp';
    const avatar = user.avatar ? user.avatar : defaultAvatar;

    return `
        <button id="back-to-home" class="back-button">← На главную</button>

    <div class="dashboard">
      <aside class="sidebar">
        <div class="user-info">
          <img src="${avatar}" alt="${user.name}" class="avatar">
          <span class="user-name">${user.name}</span>
        </div>
        <nav class="menu">
          <ul id="channel-list">
            <!-- Список каналов будет вставлен здесь -->
          </ul>
        </nav>
      </aside>
      <main class="content">
        <h1>Добро пожаловать, ${user.name}!</h1>
        <p>Выберите канал для управления.</p>
        <div id="channel-functionality">
          <!-- Функционал выбранного канала будет отображаться здесь -->
        </div>
      </main>
    </div>
  `;
}
