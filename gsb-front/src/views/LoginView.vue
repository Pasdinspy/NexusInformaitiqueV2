<template>
  <div class="login-body">
    <div class="login-container">
      <h2>Connexion</h2>
      <div v-if="erreur" class="message-erreur">
        {{ erreur }}
      </div>
      <form @submit.prevent="gererConnexion">
        <div class="form-group">
          <label for="username">Nom d'utilisateur :</label>
          <input 
            class="login-input" 
            type="text" 
            id="username" 
            v-model="formulaireConnexion.username" 
            required
            :disabled="chargement"
          >
        </div>

        <div class="form-group">
          <label for="password">Mot de passe :</label>
          <input 
            class="login-input" 
            type="password" 
            id="password" 
            v-model="formulaireConnexion.password" 
            required
            :disabled="chargement"
          >
        </div>

        <button 
          type="submit" 
          class="login-button"
          :disabled="chargement"
        >
          {{ chargement ? 'Connexion en cours...' : 'Se connecter' }}
        </button>
      </form>
    </div>
    <div class="GSB-div">
      <img src="@/assets/gsb-logo.png" alt="Logo GSB" class="GSB-img">
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

export default {
  name: 'LoginView',
  setup() {
    const router = useRouter()
    const store = useStore()
    const formulaireConnexion = ref({
      username: '',
      password: ''
    })

    const chargement = computed(() => store.state.auth.chargement)
    const erreur = computed(() => store.state.auth.erreur)

    const gererConnexion = async () => {
      try {
        const resultat = await store.dispatch('auth/connexion', formulaireConnexion.value)
        
        // Redirection selon le rôle
        switch(resultat.role) {
          case 'VISITEUR_MEDICAL':
            router.push(`/fiche-frais?role=${resultat.role}&VIS_ID=${resultat.VIS_ID}`)
            break
          case 'ADMINISTRATEUR':
          case 'COMPTABLE':
            router.push(`/fiche-frais?role=${resultat.role}`)
            break
          default:
            throw new Error('Rôle non reconnu')
        }
      } catch (erreur) {
        console.error('Erreur de connexion:', erreur)
      }
    }

    return {
      formulaireConnexion,
      gererConnexion,
      chargement,
      erreur
    }
  }
}
</script>

<style scoped>
.message-erreur {
  color: #dc3545;
  margin-bottom: 1rem;
  padding: 0.5rem;
  background-color: rgba(220, 53, 69, 0.1);
  border-radius: 4px;
  text-align: center;
}

</style>