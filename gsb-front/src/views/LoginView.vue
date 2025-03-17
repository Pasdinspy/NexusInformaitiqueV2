<template>
  <div class="login-body">
    <div class="login-container">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="username">Nom d'utilisateur :</label>
          <input 
            class="login-input" 
            type="text" 
            id="username" 
            v-model="loginForm.username" 
            required
          >
        </div>

        <div class="form-group">
          <label for="password">Mot de passe :</label>
          <input 
            class="login-input" 
            type="password" 
            id="password" 
            v-model="loginForm.password" 
            required
          >
        </div>

        <button type="submit" class="login-button">Se connecter</button>
      </form>
    </div>
    <div class="GSB-div">
      <img src="@/assets/gsb-logo.png" alt="GSB Logo" class="GSB-img">
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

export default {
  name: 'LoginView',
  setup() {
    const router = useRouter()
    const store = useStore()
    const loginForm = ref({
      username: '',
      password: ''
    })

    const handleLogin = async () => {
      try {
        await store.dispatch('auth/login', loginForm.value)
        router.push('/fiche-frais')
      } catch (error) {
        console.error('Erreur de connexion:', error)
      }
    }

    return {
      loginForm,
      handleLogin
    }
  }
}
</script>