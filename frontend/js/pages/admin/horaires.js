import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getHoraires, createHoraire, updateHoraire, deleteHoraire } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');
const JOURS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

async function load() {
    const user = await requireRole([1, 2]);
    if (!user) return;

    const horaires = await getHoraires();
    app.innerHTML = `
        <h1>Horaires</h1>
        <a href="dashboard.html">← Dashboard</a>
        <h2>Ajouter</h2>
        <form id="form-h">
            <select name="jour">${JOURS.map(j => `<option>${j}</option>`).join('')}</select>
            <input type="time" name="heure_ouverture" placeholder="Ouverture">
            <input type="time" name="heure_fermeture" placeholder="Fermeture">
            <button type="submit">Ajouter</button>
        </form>
        <table>
            <thead><tr><th>Jour</th><th>Ouverture</th><th>Fermeture</th><th>Actions</th></tr></thead>
            <tbody>
            ${horaires.map(h => `
                <tr>
                    <td>${esc(h.jour)}</td>
                    <td><input type="time" class="h-o" data-id="${esc(h.horaire_id)}" value="${esc(h.heure_ouverture ?? '')}"></td>
                    <td><input type="time" class="h-f" data-id="${esc(h.horaire_id)}" value="${esc(h.heure_fermeture ?? '')}"></td>
                    <td>
                        <button class="btn-up"  data-id="${esc(h.horaire_id)}" data-jour="${esc(h.jour)}">Sauver</button>
                        <button class="btn-del" data-id="${esc(h.horaire_id)}">Supprimer</button>
                    </td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-h').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createHoraire(Object.fromEntries(new FormData(e.target)));
            load();
        } catch (ex) { alert(ex.message); }
    });

    document.querySelectorAll('.btn-up').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            try {
                await updateHoraire({
                    id,
                    jour: btn.dataset.jour,
                    heure_ouverture: document.querySelector(`.h-o[data-id="${id}"]`).value,
                    heure_fermeture: document.querySelector(`.h-f[data-id="${id}"]`).value,
                });
                load();
            } catch (ex) { alert(ex.message); }
        });
    });

    document.querySelectorAll('.btn-del').forEach(btn => {
        btn.addEventListener('click', async () => {
            try { await deleteHoraire(btn.dataset.id); load(); } catch (ex) { alert(ex.message); }
        });
    });
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
