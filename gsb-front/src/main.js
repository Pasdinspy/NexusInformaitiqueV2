import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/index.css'  // Import du fichier CSS

const app = createApp(App)
app.use(router)
app.use(store)
app.mount('#app')