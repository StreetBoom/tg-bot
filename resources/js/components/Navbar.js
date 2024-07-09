export default function Navbar(user) {
    const defaultAvatar = '/images/default-avatar.webp'; // Путь к заглушке
    const avatar = user.avatar ? user.avatar : defaultAvatar;
    return `
    <nav class="navbar">
      <div class="container-nav">
        <a class="navbar-brand" href="#">Telegram Bot</a>
        <div class="nav-links">
          ${user.isAuthenticated ? `
            <div class="user-info">
              <a href="/dashboard">
                <img src="${avatar}" alt="${user.name}" class="avatar">
                <span>${user.name}</span>
              </a>
              <a href="/logout" class="btn-logout">Выйти</a>
            </div>
          ` : `
            <a href="/login" class="btn">Войти</a>
          `}
        </div>
      </div>
    </nav>
  `;
}
