import { renderNavbar } from '../components/navbar.js';
import { requireRole } from '../utils/auth.js';
import { getMesCommandes, deleteCommande, createAvis } from '../services/api.js';

renderNavbar();

const app = document.getElementById('app');

const STATUTS = ['en attente', 'acceptée', 'en préparation', 'en livraison', 'terminée', 'refusée'];

async function load() {
    const user = await requireRole([3]);
    if (!user) return;

    const commandes = await getMesCommandes();

    if (commandes.length === 0) {
        app.innerHTML = `
            <h1>Mon espace</h1>
            <p>Aucune commande.</p>
            <a href="/viteetgourmand/frontend/pages/menu.html" class="btn">Voir les menus</a>
        `;
        return;
    }

    app.innerHTML = `
        <h1>Mes commandes</h1>
        <a href="/viteetgourmand/frontend/pages/menu.html" class="btn">Commander un menu</a>
        <div id="msg" style="display:none" class="success-message"></div>
        <table>
            <thead><tr>
                <th>Numéro</th><th>Menu</th><th>Personnes</th>
                <th>Date prestation</th><th>Prix total</th><th>Statut</th><th>Actions</th>
            </tr></thead>
            <tbody>
            ${commandes.map(c => `
                <tr>
                    <td>${c.numero_commande}</td>
                    <td>${c.menu_titre ?? c.menu_id}</td>
                    <td>${c.nombre_personne}</td>
                    <td>${c.date_prestation ?? '-'}</td>
                    <td>${(parseFloat(c.prix_menu) + parseFloat(c.prix_livraison)).toFixed(2)} €</td>
                    <td>${c.statut}</td>
                    <td>
                        ${c.statut !== 'acceptée' && c.statut !== 'terminée'
                            ? `<button class="btn-delete" data-id="${c.numero_commande}">Annuler</button>`
                            : ''
                        }
                        ${c.statut === 'terminée'
                            ? `<button class="btn-avis" data-id="${c.numero_commande}">Donner un avis</button>`
                            : ''
                        }
                    </td>
                </tr>
            `).join('')}
            </tbody>
        </table>
        <div id="form-avis" style="display:none">
            <h2>Votre avis</h2>
            <form id="avis-form">
                <label>Note (1-5)<br><input type="number" name="note" min="1" max="5" required></label>
                <label>Commentaire<br><textarea name="description" required></textarea></label>
                <button type="submit">Envoyer</button>
                <button type="button" id="cancel-avis">Annuler</button>
            </form>
        </div>
    `;

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Annuler cette commande ?')) return;
            try {
                await deleteCommande(btn.dataset.id);
                load();
            } catch (ex) {
                alert(ex.message);
            }
        });
    });

    document.querySelectorAll('.btn-avis').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('form-avis').style.display = 'block';
        });
    });

    document.getElementById('cancel-avis')?.addEventListener('click', () => {
        document.getElementById('form-avis').style.display = 'none';
    });

    document.getElementById('avis-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createAvis(Object.fromEntries(new FormData(e.target)));
            document.getElementById('msg').textContent = 'Avis envoyé !';
            document.getElementById('msg').style.display = 'block';
            document.getElementById('form-avis').style.display = 'none';
        } catch (ex) {
            alert(ex.message);
        }
    });
}

load();
