import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { BASE } from '../../utils/config.js';

renderLayout();

const app = document.getElementById('app');
const base = `${BASE}/pages/admin`;

async function load() {
    const user = await requireRole([2, 1]);
    if (!user) return;

    app.innerHTML = `
        <h1 class="title">Dashboard Employé</h1>
        <div class="dashboard">
            <div class="dashboard-card">
                <h2>Commandes</h2>
                <p>Gérer les commandes clients</p>
                <a class="dashboard-btn" href="${base}/commandes.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Menus</h2>
                <p>Créer, modifier et supprimer les menus</p>
                <a class="dashboard-btn" href="${base}/menus.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Plats</h2>
                <p>Créer, modifier et supprimer les plats + allergènes</p>
                <a class="dashboard-btn" href="${base}/plats.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Allergènes</h2>
                <p>Gérer les allergènes disponibles</p>
                <a class="dashboard-btn" href="${base}/allergenes.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Avis clients</h2>
                <p>Valider ou refuser les avis</p>
                <a class="dashboard-btn" href="${base}/avis.html">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Horaires</h2>
                <p>Modifier et gérer les horaires</p>
                <a class="dashboard-btn" href="${base}/horaires.html">Accéder</a>
            </div>
        </div>
    `;
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
