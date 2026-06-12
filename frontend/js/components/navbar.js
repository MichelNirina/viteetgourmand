import { getCurrentUser } from '../utils/auth.js';
import { logout } from '../services/api.js';
import { BASE } from '../utils/config.js';

export async function renderNavbar(containerId = 'navbar') {
    const user = await getCurrentUser();
    const el   = document.getElementById(containerId);
    if (!el) return;

    let links = `
        <a href="${BASE}/index.html">Accueil</a>
        <a href="${BASE}/pages/menu.html">Menus</a>
        <a href="${BASE}/pages/contact.html">Contact</a>
    `;

    if (user) {
        links += `<a href="${BASE}/pages/profil.html">Mon profil</a>`;
        if (user.role_id == 1) {
            links += `<a href="${BASE}/pages/admin/dashboard.html">Dashboard Admin</a>`;
        } else if (user.role_id == 2) {
            links += `<a href="${BASE}/pages/employee/dashboard.html">Dashboard Employé</a>`;
        } else {
            links += `<a href="${BASE}/pages/client/dashboard.html">Mes commandes</a>`;
        }
        links += `<button id="btn-logout">Déconnexion (${user.prenom})</button>`;
    } else {
        links += `
            <a href="${BASE}/pages/login.html">Connexion</a>
            <a href="${BASE}/pages/register.html">Inscription</a>
        `;
    }

    el.innerHTML = `
        <div class="header">
            <h1>Vite &amp; Gourmand</h1>
        </div>
        <div class="navbar">${links}</div>
    `;

    document.getElementById('btn-logout')?.addEventListener('click', async () => {
        await logout();
        window.location.href = `${BASE}/index.html`;
    });
}
