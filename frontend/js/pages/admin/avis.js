import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getAllAvis, validerAvis, refuserAvis, deleteAvis } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1, 2]);
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
                    <td>${esc(a.prenom)}</td>
                    <td>${esc(a.note)}/5</td>
                    <td>${esc(a.description)}</td>
                    <td>${esc(a.statut)}</td>
                    <td>
                        ${a.statut !== 'valide'  ? `<button class="btn-val" data-id="${esc(a.avis_id)}">Valider</button>` : ''}
                        ${a.statut !== 'refuse'  ? `<button class="btn-ref" data-id="${esc(a.avis_id)}">Refuser</button>` : ''}
                        <button class="btn-del" data-id="${esc(a.avis_id)}">Supprimer</button>
                    </td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.querySelectorAll('.btn-val').forEach(b => b.addEventListener('click', async () => {
        try { await validerAvis(b.dataset.id); load(); } catch (ex) { alert(ex.message); }
    }));
    document.querySelectorAll('.btn-ref').forEach(b => b.addEventListener('click', async () => {
        try { await refuserAvis(b.dataset.id); load(); } catch (ex) { alert(ex.message); }
    }));
    document.querySelectorAll('.btn-del').forEach(b => b.addEventListener('click', async () => {
        if (!confirm('Supprimer cet avis ?')) return;
        try { await deleteAvis(b.dataset.id); load(); } catch (ex) { alert(ex.message); }
    }));
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
