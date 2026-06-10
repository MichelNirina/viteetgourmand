import { renderNavbar } from '../components/navbar.js';
import { requireRole } from '../utils/auth.js';
import { getToutesCommandes, updateStatutEmploye } from '../services/api.js';

renderNavbar();

const app = document.getElementById('app');
const STATUTS = ['en attente', 'acceptée', 'en préparation', 'en livraison', 'terminée', 'refusée'];

async function load() {
    const user = await requireRole([2, 1]);
    if (!user) return;

    const commandes = await getToutesCommandes();

    app.innerHTML = `
        <h1>Gestion des commandes</h1>
        <div id="msg" class="success-message" style="display:none"></div>
        ${commandes.length === 0
            ? '<p>Aucune commande.</p>'
            : `<table>
                <thead><tr>
                    <th>Numéro</th><th>Client</th><th>Menu</th>
                    <th>Personnes</th><th>Date</th><th>Statut</th><th>Action</th>
                </tr></thead>
                <tbody>
                ${commandes.map(c => `
                    <tr>
                        <td>${c.numero_commande}</td>
                        <td>${c.client_prenom}</td>
                        <td>${c.menu_titre}</td>
                        <td>${c.nombre_personne}</td>
                        <td>${c.date_prestation ?? '-'}</td>
                        <td>
                            <select class="statut-select" data-id="${c.numero_commande}">
                                ${STATUTS.map(s =>
                                    `<option value="${s}" ${s === c.statut ? 'selected' : ''}>${s}</option>`
                                ).join('')}
                            </select>
                        </td>
                        <td><button class="btn-statut" data-id="${c.numero_commande}">Mettre à jour</button></td>
                    </tr>
                `).join('')}
                </tbody>
            </table>`
        }
    `;

    document.querySelectorAll('.btn-statut').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id     = btn.dataset.id;
            const statut = document.querySelector(`.statut-select[data-id="${id}"]`).value;
            try {
                await updateStatutEmploye({ numero_commande: id, statut });
                const msg = document.getElementById('msg');
                msg.textContent = 'Statut mis à jour';
                msg.style.display = 'block';
                setTimeout(() => msg.style.display = 'none', 2000);
            } catch (ex) {
                alert(ex.message);
            }
        });
    });
}

load();
