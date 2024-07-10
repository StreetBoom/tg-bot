export default function Navbar(user, botName) {
    const defaultAvatar = '/images/default-avatar.webp'; // Путь к заглушке
    const avatar = user.avatar ? user.avatar : defaultAvatar;

    return `
    <nav class="navbar">
      <div class="container-navbar">
        <a class="navbar-brand" href="https://t.me/${botName}">${botName}</a>
        <div class="nav-links">
          ${user.isAuthenticated ? `
            <div class="user-info">
              <a href="/dashboard">
                <img src="${avatar}" alt="${user.name}" class="avatar">
                <span>${user.name}</span>
              </a>
              <button class="btn-logout" id="logout-btn">Выйти</button>
            </div>
          ` : `
            <button class="btn" id="login-btn">Войти</button>
          `}
        </div>
      </div>
    </nav>

    <div id="login-modal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Авторизация через Telegram</h2>
        <p>Авторизация происходит через Telegram. Нажмите на кнопку ниже, чтобы перейти в Telegram канал и авторизоваться.</p>
        <a href="https://t.me/${botName}" class="btn">Перейти в Telegram</a>
      </div>
    </div>
  `;
}
