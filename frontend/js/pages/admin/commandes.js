import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getToutesCommandes, updateStatutEmploye } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');
const STATUTS = ['en attente', 'acceptée', 'en préparation', 'en cours de livraison', 'livré', 'en attente du retour de matériel', 'terminée'];

async function load() {
    const user = await requireRole([1, 2]);
    if (!user) return;

    const commandes = await getToutesCommandes();
    app.innerHTML = `
        <h1>Commandes</h1>
        <a href="dashboard.html">← Dashboard</a>
        <div id="msg" class="success-message" style="display:none"></div>
        <table>
            <thead><tr><th>Numéro</th><th>Client</th><th>Menu</th><th>Personnes</th><th>Date</th><th>Total</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
            ${commandes.map(c => `
                <tr>
                    <td>${esc(c.numero_commande)}</td>
                    <td>${esc(c.client_prenom)}</td>
                    <td>${esc(c.menu_titre)}</td>
                    <td>${esc(c.nombre_personne)}</td>
                    <td>${esc(c.date_prestation ?? '-')}</td>
                    <td>${esc((parseFloat(c.prix_menu) + parseFloat(c.prix_livraison)).toFixed(2))} €</td>
                    <td>
                        <select class="sel" data-id="${esc(c.numero_commande)}">
                            ${STATUTS.map(s => `<option ${s === c.statut ? 'selected' : ''}>${esc(s)}</option>`).join('')}
                        </select>
                    </td>
                    <td><button class="btn-up" data-id="${esc(c.numero_commande)}">Sauver</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.querySelectorAll('.btn-up').forEach(btn => {
        btn.addEventListener('click', async () => {
            try {
                const statut = document.querySelector(`.sel[data-id="${btn.dataset.id}"]`).value;
                await updateStatutEmploye({ numero_commande: btn.dataset.id, statut });
                const msg = document.getElementById('msg');
                msg.textContent = 'Statut mis à jour';
                msg.style.display = 'block';
                setTimeout(() => msg.style.display = 'none', 2000);
            } catch (ex) { alert(ex.message); }
        });
    });
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
