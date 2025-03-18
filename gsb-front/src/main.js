import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/index.css'  // Import du fichier CSS

const app = createApp(App)

app.use(store)
app.use(router)

// Rendre le store disponible pour le guard de navigation
router.app = app

app.mount('#app')