import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getToutesCommandes, updateStatutEmploye } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');
const STATUTS = ['en attente', 'acceptée', 'en préparation', 'en livraison', 'terminée', 'refusée'];

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const commandes = await getToutesCommandes();
    app.innerHTML = `
        <h1>Commandes</h1>
        <a href="dashboard.html">← Dashboard</a>
        <div id="msg" class="success-msg" style="display:none"></div>
        <table>
            <thead><tr><th>Numéro</th><th>Client</th><th>Menu</th><th>Personnes</th><th>Date</th><th>Total</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
            ${commandes.map(c => `
                <tr>
                    <td>${c.numero_commande}</td>
                    <td>${c.client_prenom}</td>
                    <td>${c.menu_titre}</td>
                    <td>${c.nombre_personne}</td>
                    <td>${c.date_prestation ?? '-'}</td>
                    <td>${(parseFloat(c.prix_menu) + parseFloat(c.prix_livraison)).toFixed(2)} €</td>
                    <td><select class="sel" data-id="${c.numero_commande}">${STATUTS.map(s=>`<option ${s===c.statut?'selected':''}>${s}</option>`).join('')}</select></td>
                    <td><button class="btn-up" data-id="${c.numero_commande}">Sauver</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;
    document.querySelectorAll('.btn-up').forEach(btn => {
        btn.addEventListener('click', async () => {
            const statut = document.querySelector(`.sel[data-id="${btn.dataset.id}"]`).value;
            await updateStatutEmploye({ numero_commande: btn.dataset.id, statut });
            const msg = document.getElementById('msg');
            msg.textContent = 'Statut mis à jour'; msg.style.display = 'block';
            setTimeout(() => msg.style.display = 'none', 2000);
        });
    });
}
load();
