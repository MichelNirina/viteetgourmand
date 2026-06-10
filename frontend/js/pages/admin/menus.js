import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getMenusAdmin, createMenu, updateMenu, deleteMenu, getFilters } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const [menus, filters] = await Promise.all([getMenusAdmin(), getFilters()]);

    const opts = (arr, id, label) => arr.map(v => `<option value="${v.id ?? v}">${v.libelle ?? v}</option>`).join('');
    const themeOpts  = filters.themes.map(t  => `<option value="${t}">${t}</option>`).join('');
    const regimeOpts = filters.regimes.map(r => `<option value="${r}">${r}</option>`).join('');

    app.innerHTML = `
        <h1>Gestion des menus</h1>
        <a href="dashboard.html">← Dashboard</a>
        <div id="msg" class="success-msg" style="display:none"></div>
        <h2>Ajouter un menu</h2>
        <form id="form-menu">
            <input type="text"   name="titre"           placeholder="Titre *" required>
            <textarea            name="description"     placeholder="Description"></textarea>
            <input type="number" name="nombre_personne"   placeholder="Nb personnes min *" required>
            <input type="number" name="prix_par_personne" placeholder="Prix/personne *"    required step="0.01">
            <input type="number" name="quantite_restante" placeholder="Stock"              value="0">
            <select name="theme_nom"><option value="">Thème</option>${themeOpts}</select>
            <select name="regime_nom"><option value="">Régime</option>${regimeOpts}</select>
            <input type="hidden" name="theme_id"  value="1">
            <input type="hidden" name="regime_id" value="1">
            <button type="submit">Ajouter</button>
        </form>
        <h2>Menus existants</h2>
        <table>
            <thead><tr><th>Titre</th><th>Prix</th><th>Thème</th><th>Régime</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody>
            ${menus.map(m => `
                <tr>
                    <td>${m.titre}</td>
                    <td>${m.prix_par_personne} €</td>
                    <td>${m.theme_libelle ?? '-'}</td>
                    <td>${m.regime_libelle ?? '-'}</td>
                    <td>${m.quantite_restante}</td>
                    <td><button class="btn-del" data-id="${m.menu_id}">Supprimer</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-menu').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createMenu(Object.fromEntries(new FormData(e.target)));
            e.target.reset(); load();
        } catch (ex) { alert(ex.message); }
    });

    document.querySelectorAll('.btn-del').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Supprimer ce menu ?')) return;
            await deleteMenu(btn.dataset.id); load();
        });
    });
}
load();
