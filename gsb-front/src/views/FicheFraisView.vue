<template>
  <div class="fiche-frais-container">
    <div v-if="utilisateur" class="content">
      <h1>Fiche de Frais</h1>
      
      <!-- Informations de l'utilisateur -->
      <div class="user-info">
        <h2>Informations de l'utilisateur</h2>
        <div class="info-card">
          <p><strong>Rôle :</strong> {{ utilisateur.role }}</p>
          <p v-if="utilisateur.VIS_ID"><strong>ID Visiteur :</strong> {{ utilisateur.VIS_ID }}</p>
        </div>
      </div>

      <!-- Message de test -->
      <div class="test-message">
        <p>Connexion réussie ! La page fiche de frais est accessible.</p>
      </div>

      <!-- Bouton de déconnexion -->
      <button @click="deconnexion" class="logout-button">
        Se déconnecter
      </button>
    </div>

    <div v-else class="error-message">
      <p>Veuillez vous connecter pour accéder à cette page.</p>
      <button @click="retourLogin" class="return-button">
        Retour à la page de connexion
      </button>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'FicheFraisView',
  setup() {
    const store = useStore()
    const router = useRouter()

    const utilisateur = computed(() => store.state.auth.utilisateur)

    const deconnexion = () => {
      store.commit('auth/DEFINIR_UTILISATEUR', null)
      router.push('/login')
    }

    const retourLogin = () => {
      router.push('/login')
    }

    // Si l'utilisateur n'est pas connecté, rediriger vers login
    if (!utilisateur.value) {
      router.push('/login')
    }

    return {
      utilisateur,
      deconnexion,
      retourLogin
    }
  }
}
</script>

<style scoped>
.fiche-frais-container {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
}

.content {
  background-color: #ffffff;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #2c3e50;
  margin-bottom: 2rem;
  text-align: center;
}

.user-info {
  margin-bottom: 2rem;
}

.info-card {
  background-color: #f8f9fa;
  padding: 1rem;
  border-radius: 4px;
  margin-top: 1rem;
}

.test-message {
  background-color: #d4edda;
  color: #155724;
  padding: 1rem;
  border-radius: 4px;
  margin: 1rem 0;
  text-align: center;
}

.error-message {
  background-color: #f8d7da;
  color: #721c24;
  padding: 2rem;
  border-radius: 4px;
  text-align: center;
  margin-top: 2rem;
}

.logout-button, .return-button {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  margin-top: 1rem;
  width: 100%;
  transition: background-color 0.3s;
}

.return-button {
  background-color: #6c757d;
}

.logout-button:hover {
  background-color: #c82333;
}

.return-button:hover {
  background-color: #5a6268;
}

button:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

@media (max-width: 600px) {
  .fiche-frais-container {
    margin: 1rem;
    padding: 0.5rem;
  }

  .content {
    padding: 1rem;
  }
}
</style>