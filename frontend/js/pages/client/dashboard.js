import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getMesCommandes, updateCommande, deleteCommande, createAvis } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';
import { BASE } from '../../utils/config.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([3]);
    if (!user) return;

    const commandes = await getMesCommandes();

    if (commandes.length === 0) {
        app.innerHTML = `
            <h1>Mes commandes</h1>
            <p>Aucune commande.</p>
            <a href="${BASE}/pages/menu.html" class="btn">Voir les menus</a>
        `;
        return;
    }

    app.innerHTML = `
        <h1 class="title">Mes commandes</h1>
        <a href="${BASE}/pages/menu.html" class="btn">Commander un menu</a>
        <div id="msg" style="display:none" class="success-message"></div>
        <table class="commande-table">
            <thead><tr>
                <th>Numéro</th><th>Menu</th><th>Personnes</th>
                <th>Date prestation</th><th>Prix total</th><th>Statut</th><th>Actions</th>
            </tr></thead>
            <tbody>
            ${commandes.map(c => `
                <tr id="row-${esc(c.numero_commande)}">
                    <td>${esc(c.numero_commande)}</td>
                    <td>${esc(c.menu_titre ?? c.menu_id)}</td>
                    <td>${esc(c.nombre_personne)}</td>
                    <td>${esc(c.date_prestation ?? '-')}</td>
                    <td>${esc((parseFloat(c.prix_menu) + parseFloat(c.prix_livraison)).toFixed(2))} €</td>
                    <td>${esc(c.statut)}</td>
                    <td>
                        ${c.statut === 'en attente' ? `
                            <button class="btn-edit"   data-id="${esc(c.numero_commande)}">Modifier</button>
                            <button class="btn-delete" data-id="${esc(c.numero_commande)}">Annuler</button>
                        ` : ''}
                        ${c.statut === 'en préparation' ? `<span style="color:orange">En cours de traitement</span>` : ''}
                        ${c.statut === 'terminée' ? `
                            <button class="btn-avis" data-id="${esc(c.numero_commande)}">Donner un avis</button>
                        ` : ''}
                    </td>
                </tr>
                <tr id="edit-${esc(c.numero_commande)}" style="display:none">
                    <td colspan="7">
                        <form class="form-edit-commande">
                            <input type="hidden" name="numero_commande" value="${esc(c.numero_commande)}">
                            <input type="hidden" name="menu_id"         value="${esc(c.menu_id)}">
                            <label>Date de prestation<br>
                                <input type="date" name="date_prestation" value="${esc(c.date_prestation ?? '')}" required>
                            </label>
                            <label>Heure de livraison<br>
                                <input type="time" name="heure_livraison" value="${esc(c.heure_livraison ?? '')}">
                            </label>
                            <label>Nombre de personnes<br>
                                <input type="number" name="nombre_personne" value="${esc(c.nombre_personne)}" min="1" required>
                            </label>
                            <button type="submit">Enregistrer</button>
                            <button type="button" class="btn-cancel" data-id="${esc(c.numero_commande)}">Annuler</button>
                        </form>
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

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById(`edit-${btn.dataset.id}`).style.display = '';
        });
    });

    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById(`edit-${btn.dataset.id}`).style.display = 'none';
        });
    });

    document.querySelectorAll('.form-edit-commande').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                await updateCommande(Object.fromEntries(new FormData(e.target)));
                load();
            } catch (ex) { alert(ex.message); }
        });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Annuler cette commande ?')) return;
            try {
                await deleteCommande(btn.dataset.id);
                load();
            } catch (ex) { alert(ex.message); }
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
            const msg = document.getElementById('msg');
            msg.textContent = 'Avis envoyé !';
            msg.style.display = 'block';
            document.getElementById('form-avis').style.display = 'none';
        } catch (ex) { alert(ex.message); }
    });
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
