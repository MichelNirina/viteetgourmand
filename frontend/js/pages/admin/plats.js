import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getPlats, deletePlat, getMenusAdmin, getAllergenes } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const [plats, menus, allergenes] = await Promise.all([getPlats(), getMenusAdmin(), getAllergenes()]);

    app.innerHTML = `
        <h1>Plats</h1>
        <a href="dashboard.html">← Dashboard</a>
        <h2>Ajouter un plat</h2>
        <form id="form-plat" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre *" required>
            <select name="menu_id" required>
                <option value="">Choisir un menu</option>
                ${menus.map(m => `<option value="${m.menu_id}">${m.titre}</option>`).join('')}
            </select>
            <label>Photo<br><input type="file" name="photo" accept="image/*"></label>
            <fieldset>
                <legend>Allergènes</legend>
                ${allergenes.map(a => `
                    <label><input type="checkbox" name="allergenes[]" value="${a.allergene_id}"> ${a.libelle}</label>
                `).join('')}
            </fieldset>
            <button type="submit">Ajouter</button>
        </form>
        <h2>Liste des plats</h2>
        <table>
            <thead><tr><th>Titre</th><th>Menu</th><th>Action</th></tr></thead>
            <tbody>
            ${plats.map(p => `
                <tr>
                    <td>${p.titre_plat}</td>
                    <td>${p.menu_id}</td>
                    <td><button class="del" data-id="${p.plat_id}">Supprimer</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-plat').addEventListener('submit', async (e) => {
        e.preventDefault();
        const API = 'http://localhost/viteetgourmand/backend/public';
        const formData = new FormData(e.target);
        const res = await fetch(`${API}?page=plat&action=store`, {
            method: 'POST', credentials: 'include', body: formData
        });
        const data = await res.json();
        if (!res.ok) { alert(data.error); return; }
        e.target.reset(); load();
    });

    document.querySelectorAll('.del').forEach(b => {
        b.addEventListener('click', async () => { if (confirm('Supprimer ?')) { await deletePlat(b.dataset.id); load(); } });
    });
}
load();
