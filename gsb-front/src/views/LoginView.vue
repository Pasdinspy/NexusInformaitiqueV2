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
            placeholder="Entrez votre nom d'utilisateur"
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
            placeholder="Entrez votre mot de passe"
          >
        </div>

        <button 
          type="submit" 
          class="login-button"
          :disabled="chargement || !formulaireValide"
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
import { ref, computed, onBeforeUnmount } from 'vue'
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
    const formulaireValide = computed(() => 
      formulaireConnexion.value.username.length > 0 && 
      formulaireConnexion.value.password.length > 0
    )

    const gererConnexion = async () => {
      if (!formulaireValide.value) return

      console.log('Début de la tentative de connexion')
      try {
        store.commit('auth/DEFINIR_ERREUR', null)
        store.commit('auth/DEFINIR_CHARGEMENT', true)

        const resultat = await store.dispatch('auth/connexion', formulaireConnexion.value)
        console.log('Résultat de la connexion:', resultat)

        if (!resultat || !resultat.role) {
          throw new Error('Réponse invalide du serveur')
        }

        // Attendre un court instant avant la redirection
        await new Promise(resolve => setTimeout(resolve, 100))

        // Construction de l'objet de redirection
        const routeConfig = {
          name: 'fiche-frais',
          query: { role: resultat.role }
        }

        if (resultat.VIS_ID) {
          routeConfig.query.VIS_ID = resultat.VIS_ID
        }

        console.log('Tentative de redirection avec:', routeConfig)

        try {
          await router.push(routeConfig)
          console.log('Redirection réussie')
        } catch (erreurRedirection) {
          console.error('Erreur de redirection:', erreurRedirection)
          if (erreurRedirection.name === 'NavigationDuplicated') {
            // Ignorer l'erreur de navigation dupliquée
            return
          }
          throw new Error('Erreur lors de la redirection: ' + erreurRedirection.message)
        }

      } catch (erreur) {
        console.error('Erreur complète:', erreur)
        store.commit('auth/DEFINIR_ERREUR', 
          erreur.message || 'Une erreur est survenue lors de la connexion'
        )
        formulaireConnexion.value.password = ''
      } finally {
        store.commit('auth/DEFINIR_CHARGEMENT', false)
      }
    }

    // Nettoyage au démontage
    onBeforeUnmount(() => {
      store.commit('auth/DEFINIR_ERREUR', null)
    })

    return {
      formulaireConnexion,
      gererConnexion,
      chargement,
      erreur,
      formulaireValide
    }
  }
}
</script>


<style scoped>
.login-body {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 1rem;
}

.login-container {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  margin-bottom: 2rem;
}

h2 {
  color: #2c3e50;
  text-align: center;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

.login-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.login-input:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.login-input:disabled {
  background-color: #f8f9fa;
  cursor: not-allowed;
}

.login-button {
  width: 100%;
  padding: 0.75rem;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover:not(:disabled) {
  background-color: #2980b9;
}

.login-button:disabled {
  background-color: #95a5a6;
  cursor: not-allowed;
}

.message-erreur {
  color: #dc3545;
  margin-bottom: 1rem;
  padding: 0.75rem;
  background-color: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.2);
  border-radius: 4px;
  text-align: center;
  font-size: 0.9rem;
}

.GSB-div {
  text-align: center;
}

.GSB-img {
  max-width: 200px;
  height: auto;
}

@media (max-width: 480px) {
  .login-container {
    padding: 1.5rem;
  }

  .GSB-img {
    max-width: 150px;
  }
}
</style>