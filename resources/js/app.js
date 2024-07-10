import '../css/app.css';
import Navbar from './components/Navbar';
import Hero from './components/Hero';
import Features from './components/Features';
import About from './components/About';
import Footer from './components/Footer';

// Получение данных пользователя из метатегов, установленных в шаблоне Blade
const userMeta = document.querySelector('meta[name="user"]');
const user = userMeta ? JSON.parse(userMeta.getAttribute('content')) : { isAuthenticated: false };

document.querySelector('#app').innerHTML = `
  ${Navbar(user)}
  ${Hero()}
  ${Features()}
  ${About()}
  ${Footer()}
`;
