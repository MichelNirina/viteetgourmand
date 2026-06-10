import { renderLayout } from '../../components/layout.js';
import { requireRole } from '../../utils/auth.js';
import { getUsers, deleteUser } from '../../services/api.js';
import { esc } from '../../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const users = await getUsers();
    const roles = { 1: 'Admin', 2: 'Employé', 3: 'Client' };
    app.innerHTML = `
        <h1>Utilisateurs</h1>
        <a href="dashboard.html">← Dashboard</a>
        <table>
            <thead><tr><th>Prénom</th><th>Email</th><th>Rôle</th><th>Action</th></tr></thead>
            <tbody>
            ${users.map(u => `
                <tr>
                    <td>${esc(u.prenom)}</td>
                    <td>${esc(u.email)}</td>
                    <td>${esc(roles[u.role_id] ?? u.role_id)}</td>
                    <td><button class="del" data-id="${esc(u.user_id)}">Supprimer</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.querySelectorAll('.del').forEach(b => {
        b.addEventListener('click', async () => {
            if (!confirm('Supprimer cet utilisateur ?')) return;
            try { await deleteUser(b.dataset.id); load(); } catch (ex) { alert(ex.message); }
        });
    });
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
