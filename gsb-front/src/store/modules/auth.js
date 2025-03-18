import { serviceAuth } from '@/services/api'

export default {
  namespaced: true,
  state: {
    utilisateur: null,
    chargement: false,
    erreur: null
  },
  mutations: {
    DEFINIR_UTILISATEUR(state, utilisateur) {
      console.log('Mutation DEFINIR_UTILISATEUR:', utilisateur)
      state.utilisateur = utilisateur
    },
    DEFINIR_CHARGEMENT(state, chargement) {
      state.chargement = chargement
    },
    DEFINIR_ERREUR(state, erreur) {
      state.erreur = erreur
    }
  },
  actions: {
    async connexion({ commit }, identifiants) {
      console.log('Action connexion démarrée')
      try {
        const reponse = await serviceAuth.connexion(identifiants)
        console.log('Réponse du serveur:', reponse)
        
        if (!reponse.succes) {
          throw new Error(reponse.message || 'Échec de la connexion')
        }
        
        commit('DEFINIR_UTILISATEUR', reponse.donnees)
        return reponse.donnees
      } catch (erreur) {
        console.error('Erreur dans action connexion:', erreur)
        throw erreur
      }
    }
  },
  getters: {
    estAuthentifie: state => !!state.utilisateur,
    roleUtilisateur: state => state.utilisateur?.role
  }
}