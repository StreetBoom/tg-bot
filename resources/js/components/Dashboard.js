export default function Dashboard(user) {
    const defaultAvatar = '/images/default-avatar.webp'; // Путь к заглушке
    const avatar = user.avatar ? user.avatar : defaultAvatar;

    return `
    <div class="dashboard">
      <aside class="sidebar">
        <div class="user-info">
          <img src="${avatar}" alt="${user.name}" class="avatar">
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
