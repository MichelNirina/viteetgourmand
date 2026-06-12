import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getMenusAdmin, createMenu, updateMenu, deleteMenu, getThemes, getRegimes } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1, 2]);
    if (!user) return;

    let menus, themes, regimes;
    try {
        [menus, themes, regimes] = await Promise.all([getMenusAdmin(), getThemes(), getRegimes()]);
    } catch (err) {
        app.innerHTML = `<p class="error-message">Erreur de chargement : ${err.message}</p>`;
        return;
    }

    const themeOpts  = themes.map(t  => `<option value="${esc(t.theme_id)}">${esc(t.libelle)}</option>`).join('');
    const regimeOpts = regimes.map(r => `<option value="${esc(r.regime_id)}">${esc(r.libelle)}</option>`).join('');

    app.innerHTML = `
        <h1>Gestion des menus</h1>
        <a href="dashboard.html">← Dashboard</a>
        <div id="msg" class="success-message" style="display:none"></div>
        <h2>Ajouter un menu</h2>
        <form id="form-menu">
            <input type="text"   name="titre"             placeholder="Titre *"               required>
            <textarea            name="description"        placeholder="Description"></textarea>
            <input type="number" name="nombre_personne"   placeholder="Nb personnes min *"    required>
            <input type="number" name="prix_par_personne" placeholder="Prix / personne (€) *" required step="0.01">
            <input type="number" name="quantite_restante" placeholder="Stock"                 value="0">
            <select name="theme_id">
                <option value="0">-- Thème (optionnel) --</option>
                ${themeOpts}
            </select>
            <select name="regime_id">
                <option value="0">-- Régime (optionnel) --</option>
                ${regimeOpts}
            </select>
            <button type="submit">Ajouter</button>
        </form>
        <h2>Menus existants</h2>
        <table>
            <thead><tr><th>Titre</th><th>Prix</th><th>Thème</th><th>Régime</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody>
            ${menus.map(m => `
                <tr id="row-${esc(m.menu_id)}">
                    <td>${esc(m.titre)}</td>
                    <td>${esc(m.prix_par_personne)} €</td>
                    <td>${esc(m.theme_libelle ?? '-')}</td>
                    <td>${esc(m.regime_libelle ?? '-')}</td>
                    <td>${esc(m.quantite_restante)}</td>
                    <td>
                        <button class="btn-edit" data-id="${esc(m.menu_id)}">Modifier</button>
                        <button class="btn-del"  data-id="${esc(m.menu_id)}">Supprimer</button>
                    </td>
                </tr>
                <tr id="edit-${esc(m.menu_id)}" style="display:none">
                    <td colspan="6">
                        <form class="form-edit-menu">
                            <input type="hidden" name="menu_id" value="${esc(m.menu_id)}">
                            <input type="text"   name="titre"             value="${esc(m.titre)}"             placeholder="Titre *" required>
                            <textarea            name="description">${esc(m.description ?? '')}</textarea>
                            <input type="number" name="nombre_personne"   value="${esc(m.nombre_personne)}"   placeholder="Nb personnes" required>
                            <input type="number" name="prix_par_personne" value="${esc(m.prix_par_personne)}" step="0.01" required>
                            <input type="number" name="quantite_restante" value="${esc(m.quantite_restante)}">
                            <select name="theme_id">
                                <option value="0">-- Thème --</option>
                                ${themes.map(t => `<option value="${esc(t.theme_id)}" ${t.theme_id == m.theme_id ? 'selected' : ''}>${esc(t.libelle)}</option>`).join('')}
                            </select>
                            <select name="regime_id">
                                <option value="0">-- Régime --</option>
                                ${regimes.map(r => `<option value="${esc(r.regime_id)}" ${r.regime_id == m.regime_id ? 'selected' : ''}>${esc(r.libelle)}</option>`).join('')}
                            </select>
                            <button type="submit">Enregistrer</button>
                            <button type="button" class="btn-cancel" data-id="${esc(m.menu_id)}">Annuler</button>
                        </form>
                    </td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-menu').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createMenu(Object.fromEntries(new FormData(e.target)));
            showMsg('Menu créé !');
            e.target.reset();
            load();
        } catch (ex) { alert(ex.message); }
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById(`edit-${btn.dataset.id}`).style.display = '';
        });
    });

    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById(`edit-${btn.dataset.id}`).style.display = 'none';
        });
    });

    document.querySelectorAll('.form-edit-menu').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                await updateMenu(Object.fromEntries(new FormData(e.target)));
                showMsg('Menu mis à jour !');
                load();
            } catch (ex) { alert(ex.message); }
        });
    });

    document.querySelectorAll('.btn-del').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Supprimer ce menu ?')) return;
            try {
                await deleteMenu(btn.dataset.id);
                load();
            } catch (ex) { alert(ex.message); }
        });
    });
}

function showMsg(txt) {
    const msg = document.getElementById('msg');
    msg.textContent = txt;
    msg.style.display = 'block';
    setTimeout(() => msg.style.display = 'none', 2500);
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
