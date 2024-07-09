export default function Dashboard(user) {
    return `
    <template>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="user-info">
        <img :src="user.avatar" :alt="user.name" class="avatar">
        <span class="user-name">{{ user.name }}</span>
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
      <h1>Добро пожаловать, {{ user.name }}!</h1>
      <p>Это ваша личная страница.</p>
      <div>
        <h2>Ваши каналы</h2>
        <ul id="channel-list">
          <li v-for="channel in channels" :key="channel.channel_id">
            {{ channel.channel_name }} (ID: {{ channel.channel_id }})
          </li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  props: ['user'],
  data() {
    return {
      channels: []
    };
  },
  mounted() {
    this.loadChannels();
  },
  methods: {
    loadChannels() {
      fetch('/get-channels')
        .then(response => response.json())
        .then(data => {
          this.channels = data;
        })
        .catch(error => {
          console.error('Error loading channels:', error);
        });
    }
  }
};
</script>

<style scoped>
/* Стили для компонента Dashboard */
</style>
  `;
}
