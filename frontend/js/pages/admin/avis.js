import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getAllAvis, validerAvis, refuserAvis, deleteAvis } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const avis = await getAllAvis();
    app.innerHTML = `
        <h1>Avis clients</h1>
        <a href="dashboard.html">← Dashboard</a>
        <table>
            <thead><tr><th>Client</th><th>Note</th><th>Commentaire</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
            ${avis.map(a => `
                <tr>
                    <td>${a.prenom}</td>
                    <td>${a.note}/5</td>
                    <td>${a.description}</td>
                    <td>${a.statut}</td>
                    <td>
                        ${a.statut !== 'valide'  ? `<button class="btn-val" data-id="${a.avis_id}">Valider</button>` : ''}
                        ${a.statut !== 'refuse'  ? `<button class="btn-ref" data-id="${a.avis_id}">Refuser</button>` : ''}
                        <button class="btn-del" data-id="${a.avis_id}">Supprimer</button>
                    </td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.querySelectorAll('.btn-val').forEach(b => b.addEventListener('click', async () => { await validerAvis(b.dataset.id); load(); }));
    document.querySelectorAll('.btn-ref').forEach(b => b.addEventListener('click', async () => { await refuserAvis(b.dataset.id); load(); }));
    document.querySelectorAll('.btn-del').forEach(b => b.addEventListener('click', async () => { if (confirm('Supprimer ?')) { await deleteAvis(b.dataset.id); load(); } }));
}
load();
