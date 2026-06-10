import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getStats } from '../../services/api.js';

renderNavbar();

const app  = document.getElementById('app');
const base = '/viteetgourmand/frontend/pages/admin';

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const stats = await getStats();

    app.innerHTML = `
        <h1>Tableau de bord Admin</h1>
        <p style="text-align:center">Bienvenue, <strong>${user.prenom}</strong></p>

        <div class="stats-container">
            <div class="stats-card total-card">
                <h2>Commandes totales</h2>
                <p class="stats-number">${stats.total}</p>
            </div>
        </div>

        <h2>Gestion du restaurant</h2>
        <div class="dashboard">
            <div class="dashboard-card">
                <h2>Commandes</h2>
                <p>Gérer toutes les commandes</p>
                <a href="${base}/commandes.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Employés</h2>
                <p>Gérer les comptes employés</p>
                <a href="${base}/employees.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Menus</h2>
                <p>Créer et modifier les menus</p>
                <a href="${base}/menus.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Plats</h2>
                <p>Gérer les plats et photos</p>
                <a href="${base}/plats.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Avis clients</h2>
                <p>Modérer les avis</p>
                <a href="${base}/avis.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Horaires</h2>
                <p>Horaires d'ouverture</p>
                <a href="${base}/horaires.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Allergènes</h2>
                <p>Liste des allergènes</p>
                <a href="${base}/allergenes.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Régimes</h2>
                <p>Régimes alimentaires</p>
                <a href="${base}/regimes.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Thèmes</h2>
                <p>Thèmes d'événements</p>
                <a href="${base}/themes.html" class="dashboard-btn">Accéder</a>
            </div>
            <div class="dashboard-card">
                <h2>Utilisateurs</h2>
                <p>Gérer les comptes</p>
                <a href="${base}/users.html" class="dashboard-btn">Accéder</a>
            </div>
        </div>
    `;
}

load();
