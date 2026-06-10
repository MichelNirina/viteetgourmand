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

    el.innerHTML = `
        <div class="header">
            <h1>Vite &amp; Gourmand</h1>
            <p>Restaurant gastronomique</p>
        </div>
        <div class="navbar">${links}</div>
    `;

    document.getElementById('btn-logout')?.addEventListener('click', async () => {
        await logout();
        window.location.href = `${base}/index.html`;
    });

    renderFooter();
}

function renderFooter() {
    if (document.getElementById('site-footer')) return;
    const footer = document.createElement('footer');
    footer.id = 'site-footer';
    footer.className = 'footer';
    footer.innerHTML = `
        <p>&copy; ${new Date().getFullYear()} Vite &amp; Gourmand — Restaurant gastronomique</p>
        <p>
            <a href="/viteetgourmand/frontend/index.html">Accueil</a>
            <a href="/viteetgourmand/frontend/pages/menu.html">Menus</a>
            <a href="/viteetgourmand/frontend/pages/contact.html">Contact</a>
        </p>
    `;
    document.body.appendChild(footer);
}
