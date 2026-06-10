import { getCurrentUser } from '../utils/auth.js';
import { logout } from '../services/api.js';

export async function renderNavbar(containerId = 'navbar') {
    const user = await getCurrentUser();
    const el   = document.getElementById(containerId);
    if (!el) return;

    const base = '/viteetgourmand/frontend';

    let links = `
        <a href="${base}/index.html">Accueil</a>
        <a href="${base}/pages/menu.html">Menus</a>
        <a href="${base}/pages/contact.html">Contact</a>
    `;

    if (user) {
        if (user.role_id == 1) {
            links += `<a href="${base}/pages/admin/dashboard.html">Admin</a>`;
        } else if (user.role_id == 2) {
            links += `<a href="${base}/pages/employee/dashboard.html">Espace employé</a>`;
        } else {
            links += `<a href="${base}/pages/client/dashboard.html">Mon espace</a>`;
        }
        links += `<button id="btn-logout">Déconnexion (${user.prenom})</button>`;
    } else {
        links += `
            <a href="${base}/pages/login.html">Connexion</a>
            <a href="${base}/pages/register.html">Inscription</a>
        `;
    }

    el.innerHTML = `<nav>${links}</nav>`;

    document.getElementById('btn-logout')?.addEventListener('click', async () => {
        await logout();
        window.location.href = `${base}/index.html`;
    });
}
