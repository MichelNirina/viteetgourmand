import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getAllergenes, createAllergene, deleteAllergene } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const items = await getAllergenes();
    app.innerHTML = `
        <h1>Allergènes</h1>
        <a href="dashboard.html">← Dashboard</a>
        <form id="form">
            <input type="text" name="libelle" placeholder="Nouvel allergène" required>
            <button type="submit">Ajouter</button>
        </form>
        <ul>${items.map(i => `<li>${i.libelle} <button class="del" data-id="${i.allergene_id}">✕</button></li>`).join('')}</ul>
    `;
    document.getElementById('form').addEventListener('submit', async (e) => {
        e.preventDefault();
        await createAllergene(Object.fromEntries(new FormData(e.target))); load();
    });
    document.querySelectorAll('.del').forEach(b => b.addEventListener('click', async () => { await deleteAllergene(b.dataset.id); load(); }));
}
load();
