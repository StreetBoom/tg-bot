import Vue from 'vue';
import Navbar from './components/Navbar.vue';
import Hero from './components/Hero.vue';
import Features from './components/Features.vue';
import About from './components/About.vue';
import Footer from './components/Footer.vue';

// Получение данных пользователя из метатегов, установленных в шаблоне Blade
const userMeta = document.querySelector('meta[name="user"]');
const user = userMeta ? JSON.parse(userMeta.getAttribute('content')) : { isAuthenticated: false };

new Vue({
    el: '#app',
    data: {
        user: user
    },
    components: {
        Navbar,
        Hero,
        Features,
        About,
        Footer
    },
    template: `
    <div>
      <Navbar :user="user" />
      <Hero />
      <Features />
      <About />
      <Footer />
    </div>
  `
});

