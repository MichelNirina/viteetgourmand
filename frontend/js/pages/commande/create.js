import { renderLayout } from '../../components/layout.js';
import { requireAuth } from '../../utils/auth.js';
import { getMenu, createCommande } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';
import { BASE } from '../../utils/config.js';

renderLayout();

const app = document.getElementById('app');
const params = new URLSearchParams(window.location.search);
const menuId = params.get('menu_id');

async function load() {
    const user = await requireAuth();
    if (!user) return;

    if (!menuId) {
        app.innerHTML = `<p>Menu non spécifié. <a href="${BASE}/pages/menu.html">Retour aux menus</a></p>`;
        return;
    }

    const menu = await getMenu(menuId);

    app.innerHTML = `
        <h1>Commander : ${esc(menu.titre)}</h1>
        <p>${esc(menu.description)}</p>
        <p>Prix : ${esc(menu.prix_par_personne)} € / personne</p>
        <p>Minimum : ${esc(menu.nombre_personne)} personnes</p>
        <div id="error" class="error-message" style="display:none"></div>
        <form id="form-commande">
            <input type="hidden" name="menu_id" value="${esc(menu.menu_id)}">
            <label>Nombre de personnes (min ${esc(menu.nombre_personne)})<br>
                <input type="number" name="nombre_personne" min="${esc(menu.nombre_personne)}" required>
            </label>
            <label>Date de prestation<br>
                <input type="date" name="date_prestation" required>
            </label>
            <label>Heure de livraison<br>
                <input type="time" name="heure_livraison">
            </label>
            <button type="submit">Confirmer la commande</button>
        </form>
        <a href="${BASE}/pages/menu.html">← Retour aux menus</a>
    `;

    document.getElementById('form-commande').addEventListener('submit', async (e) => {
        e.preventDefault();
        const err = document.getElementById('error');
        err.style.display = 'none';
        try {
            await createCommande(Object.fromEntries(new FormData(e.target)));
            window.location.href = `${BASE}/pages/client/dashboard.html`;
        } catch (ex) {
            err.textContent = ex.message;
            err.style.display = 'block';
        }
    });
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
