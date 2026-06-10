import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getPlats, deletePlat, getMenusAdmin, getAllergenes } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');
const API = 'http://localhost/viteetgourmand/backend/public';

async function load() {
    const user = await requireRole([1]);
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
                ${menus.map(m => `<option value="${m.menu_id}">${m.titre}</option>`).join('')}
            </select>
            <label>Photo (jpeg/png/webp)</label>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            <fieldset>
                <legend>Allergènes</legend>
                ${allergenes.map(a => `
                    <label>
                        <input type="checkbox" name="allergenes[]" value="${a.allergene_id}"> ${a.libelle}
                    </label>
                `).join('')}
            </fieldset>
            <button type="submit">Ajouter le plat</button>
        </form>
        <div id="msg" class="success-message" style="display:none"></div>
        <h2>Liste des plats</h2>
        <table>
            <thead><tr><th>Titre</th><th>Menu</th><th>Allergènes</th><th>Action</th></tr></thead>
            <tbody>
            ${plats.map(p => `
                <tr>
                    <td>${p.titre_plat}</td>
                    <td>${menuMap[p.menu_id] ?? p.menu_id}</td>
                    <td>${p.allergenes ?? 'Aucun'}</td>
                    <td><button class="del" data-id="${p.plat_id}">Supprimer</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-plat').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const res = await fetch(`${API}?page=plat&action=store`, {
            method: 'POST', credentials: 'include', body: formData
        });
        const data = await res.json();
        if (!res.ok) { alert(data.error); return; }
        const msg = document.getElementById('msg');
        msg.textContent = 'Plat ajouté !';
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 2000);
        e.target.reset();
        load();
    });

    document.querySelectorAll('.del').forEach(b => {
        b.addEventListener('click', async () => {
            if (confirm('Supprimer ce plat ?')) { await deletePlat(b.dataset.id); load(); }
        });
    });
}
load();
