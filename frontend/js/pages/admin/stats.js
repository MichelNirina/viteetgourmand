import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getStats, getMenusAdmin } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const [stats, menus] = await Promise.all([getStats(), getMenusAdmin()]);
    const menuMap = Object.fromEntries(menus.map(m => [String(m.menu_id), m.titre]));

    app.innerHTML = `
        <h1 class="title">Statistiques</h1>
        <a href="dashboard.html">← Dashboard</a>
        <div class="stats-container">
            <div class="stats-card total-card">
                <h2>Total commandes</h2>
                <p class="stats-number">${esc(stats.total)}</p>
            </div>
            <div class="stats-card">
                <h2>Commandes par menu</h2>
                <ul class="stats-list">
                    ${(stats.by_menu ?? []).map(b => `
                        <li>${esc(menuMap[String(b.menu_id)] ?? b.menu_id)} <strong>${esc(b.total)}</strong> commandes</li>
                    `).join('') || '<li>Aucune donnée</li>'}
                </ul>
            </div>
            <div class="stats-card">
                <h2>Chiffre d'affaires</h2>
                <ul class="stats-list">
                    ${(stats.ca ?? []).map(c => `
                        <li>${esc(menuMap[String(c.menu_id)] ?? c.menu_id)} <strong>${esc(parseFloat(c.ca).toFixed(2))} €</strong></li>
                    `).join('') || '<li>Aucune donnée</li>'}
                </ul>
            </div>
        </div>
    `;
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
