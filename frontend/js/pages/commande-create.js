import { renderNavbar } from '../components/navbar.js';
import { requireAuth } from '../utils/auth.js';
import { getMenu, createCommande } from '../services/api.js';

renderNavbar();

const app = document.getElementById('app');
const params = new URLSearchParams(window.location.search);
const menuId = params.get('menu_id');

async function load() {
    await requireAuth();
    if (!menuId) {
        app.innerHTML = '<p>Menu non spécifié. <a href="/viteetgourmand/frontend/pages/menu.html">Retour aux menus</a></p>';
        return;
    }

    const menu = await getMenu(menuId);

    app.innerHTML = `
        <h1>Commander : ${menu.titre}</h1>
        <p>${menu.description}</p>
        <p>Prix : ${menu.prix_par_personne} € / personne</p>
        <p>Minimum : ${menu.nombre_personne} personnes</p>
        <div id="error" class="error-message" style="display:none"></div>
        <form id="form-commande">
            <input type="hidden" name="menu_id" value="${menu.menu_id}">
            <label>Nombre de personnes (min ${menu.nombre_personne})<br>
                <input type="number" name="nombre_personne" min="${menu.nombre_personne}" required>
            </label>
            <label>Date de prestation<br>
                <input type="date" name="date_prestation" required>
            </label>
            <label>Heure de livraison<br>
                <input type="time" name="heure_livraison">
            </label>
            <button type="submit">Confirmer la commande</button>
        </form>
        <a href="/viteetgourmand/frontend/pages/menu.html">← Retour aux menus</a>
    `;

    document.getElementById('form-commande').addEventListener('submit', async (e) => {
        e.preventDefault();
        const err = document.getElementById('error');
        err.style.display = 'none';
        try {
            await createCommande(Object.fromEntries(new FormData(e.target)));
            window.location.href = '/viteetgourmand/frontend/pages/client/dashboard.html';
        } catch (ex) {
            err.textContent = ex.message;
            err.style.display = 'block';
        }
    });
}

load();
