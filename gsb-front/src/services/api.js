import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://51.83.74.206:8000',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

export const serviceAuth = {
    connexion: async (identifiants) => {
        try {
            console.log('Envoi de la requête de connexion:', identifiants);
            const reponse = await apiClient.post('/php/login.php', identifiants);
            console.log('Réponse brute du serveur:', reponse);
            return reponse.data;
        } catch (erreur) {
            console.error('Erreur complète:', erreur);
            if (erreur.response) {
                throw new Error(erreur.response.data.message || 'Erreur serveur');
            }
            throw new Error('Impossible de contacter le serveur');
        }
    }
};