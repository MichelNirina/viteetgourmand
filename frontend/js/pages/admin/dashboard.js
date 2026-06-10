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
        <p>Bienvenue, ${user.prenom}</p>
        <div class="stats">
            <div class="card"><h3>Commandes totales</h3><p>${stats.total}</p></div>
        </div>
        <h2>Gestion</h2>
        <div class="admin-links">
            <a href="${base}/commandes.html" class="btn">Commandes</a>
            <a href="${base}/employees.html" class="btn">Employés</a>
            <a href="${base}/menus.html" class="btn">Menus</a>
            <a href="${base}/plats.html" class="btn">Plats</a>
            <a href="${base}/avis.html" class="btn">Avis</a>
            <a href="${base}/horaires.html" class="btn">Horaires</a>
            <a href="${base}/allergenes.html" class="btn">Allergènes</a>
            <a href="${base}/regimes.html" class="btn">Régimes</a>
            <a href="${base}/themes.html" class="btn">Thèmes</a>
            <a href="${base}/users.html" class="btn">Utilisateurs</a>
        </div>
    `;
}

load();
