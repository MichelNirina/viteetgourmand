import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { BASE } from '../../utils/config.js';

renderLayout();

const app = document.getElementById('app');
const base = `${BASE}/pages/admin`;

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    app.innerHTML = `
        <h1 class="title">Dashboard Administrateur</h1>
        <div class="dashboard">
            <div class="dashboard-card">
                <h2>Gestion employés</h2>
                <p>Créer, modifier et supprimer les comptes employés</p>
                <a class="dashboard-btn" href="${base}/employees.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Statistiques</h2>
                <p>Commandes, chiffre d'affaires et performances</p>
                <a class="dashboard-btn" href="${base}/stats.html">Voir</a>
            </div>
            <div class="dashboard-card">
                <h2>Commandes</h2>
                <p>Gestion globale des commandes clients</p>
                <a class="dashboard-btn" href="${base}/commandes.html">Gérer</a>
            </div>
            <div class="dashboard-card">
                <h2>Avis clients</h2>
                <p>Valider ou refuser les avis clients</p>
                <a class="dashboard-btn" href="${base}/avis.html">Gérer</a>
            </div>
            <div class="dashboard-card">
                <h2>Menus</h2>
                <p>Créer, modifier et supprimer les menus</p>
                <a class="dashboard-btn" href="${base}/menus.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Plats</h2>
                <p>Gérer les plats, photos et allergènes</p>
                <a class="dashboard-btn" href="${base}/plats.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Allergènes</h2>
                <p>Gérer les allergènes disponibles</p>
                <a class="dashboard-btn" href="${base}/allergenes.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Horaires</h2>
                <p>Modifier les horaires d'ouverture</p>
                <a class="dashboard-btn" href="${base}/horaires.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Régimes</h2>
                <p>Gérer les régimes alimentaires</p>
                <a class="dashboard-btn" href="${base}/regimes.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Thèmes</h2>
                <p>Gérer les thèmes d'événements</p>
                <a class="dashboard-btn" href="${base}/themes.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Utilisateurs</h2>
                <p>Gérer tous les comptes</p>
                <a class="dashboard-btn" href="${base}/users.html">Accéder</a>
            </div>
        </div>
    `;
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
