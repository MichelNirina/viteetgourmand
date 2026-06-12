import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getPlats, deletePlat, getMenusAdmin, getAllergenes, createPlat, updatePlat } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1, 2]);
    if (!user) return;

    const [plats, menus, allergenes] = await Promise.all([getPlats(), getMenusAdmin(), getAllergenes()]);
    const menuMap = Object.fromEntries(menus.map(m => [m.menu_id, m.titre]));

    app.innerHTML = `
        <h1>Plats</h1>
        <a href="dashboard.html">← Dashboard</a>
        <h2>Ajouter un plat</h2>
        <form id="form-plat" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre *" required>
            <select name="menu_id" required>
                <option value="">Choisir un menu *</option>
                ${menus.map(m => `<option value="${esc(m.menu_id)}">${esc(m.titre)}</option>`).join('')}
            </select>
            <label>Photo (jpeg/png/webp)</label>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            <fieldset>
                <legend>Allergènes</legend>
                ${allergenes.map(a => `
                    <label><input type="checkbox" name="allergenes[]" value="${esc(a.allergene_id)}"> ${esc(a.libelle)}</label>
                `).join('')}
            </fieldset>
            <button type="submit">Ajouter le plat</button>
        </form>
        <div id="msg" class="success-message" style="display:none"></div>
        <h2>Liste des plats</h2>
        <table>
            <thead><tr><th>Titre</th><th>Menu</th><th>Allergènes</th><th>Actions</th></tr></thead>
            <tbody>
            ${plats.map(p => `
                <tr id="row-${esc(p.plat_id)}">
                    <td>${esc(p.titre_plat)}</td>
                    <td>${esc(menuMap[p.menu_id] ?? p.menu_id)}</td>
                    <td>${esc(p.allergenes ?? 'Aucun')}</td>
                    <td>
                        <button class="btn-edit" data-id="${esc(p.plat_id)}">Modifier</button>
                        <button class="del"      data-id="${esc(p.plat_id)}">Supprimer</button>
                    </td>
                </tr>
                <tr id="edit-${esc(p.plat_id)}" style="display:none">
                    <td colspan="4">
                        <form class="form-edit-plat" enctype="multipart/form-data">
                            <input type="hidden" name="plat_id" value="${esc(p.plat_id)}">
                            <input type="text" name="titre" value="${esc(p.titre_plat)}" required placeholder="Titre *">
                            <select name="menu_id" required>
                                ${menus.map(m => `<option value="${esc(m.menu_id)}" ${m.menu_id == p.menu_id ? 'selected' : ''}>${esc(m.titre)}</option>`).join('')}
                            </select>
                            <label>Nouvelle photo</label>
                            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                            <fieldset>
                                <legend>Allergènes</legend>
                                ${allergenes.map(a => `
                                    <label><input type="checkbox" name="allergenes[]" value="${esc(a.allergene_id)}"> ${esc(a.libelle)}</label>
                                `).join('')}
                            </fieldset>
                            <button type="submit">Enregistrer</button>
                            <button type="button" class="btn-cancel" data-id="${esc(p.plat_id)}">Annuler</button>
                        </form>
                    </td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-plat').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createPlat(new FormData(e.target));
            showMsg('Plat ajouté !');
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

    document.querySelectorAll('.form-edit-plat').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                await updatePlat(new FormData(e.target));
                showMsg('Plat modifié !');
                load();
            } catch (ex) { alert(ex.message); }
        });
    });

    document.querySelectorAll('.del').forEach(b => {
        b.addEventListener('click', async () => {
            if (!confirm('Supprimer ce plat ?')) return;
            try {
                await deletePlat(b.dataset.id);
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
