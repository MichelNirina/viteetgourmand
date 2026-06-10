import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getAllergenes, createAllergene, deleteAllergene } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1, 2]);
    if (!user) return;

    const items = await getAllergenes();
    app.innerHTML = `
        <h1>Allergènes</h1>
        <a href="dashboard.html">← Dashboard</a>
        <form id="form">
            <input type="text" name="libelle" placeholder="Nouvel allergène" required>
            <button type="submit">Ajouter</button>
        </form>
        <ul>
            ${items.map(i => `<li>${esc(i.libelle)} <button class="del" data-id="${esc(i.allergene_id)}">✕</button></li>`).join('')}
        </ul>
    `;

    document.getElementById('form').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createAllergene(Object.fromEntries(new FormData(e.target)));
            load();
        } catch (ex) { alert(ex.message); }
    });

    document.querySelectorAll('.del').forEach(b => b.addEventListener('click', async () => {
        try { await deleteAllergene(b.dataset.id); load(); } catch (ex) { alert(ex.message); }
    }));
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
