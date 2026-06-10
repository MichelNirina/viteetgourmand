import { renderNavbar } from '../../components/navbar.js';
import { requireRole } from '../../utils/auth.js';
import { getEmployees, createEmployee, deleteEmployee } from '../../services/api.js';

renderNavbar();
const app = document.getElementById('app');

async function load() {
    const user = await requireRole([1]);
    if (!user) return;

    const employees = await getEmployees();
    app.innerHTML = `
        <h1>Employés</h1>
        <a href="dashboard.html">← Dashboard</a>
        <div id="msg" class="success-message" style="display:none"></div>
        <h2>Ajouter un employé</h2>
        <form id="form-emp">
            <input type="text"     name="prenom"    placeholder="Prénom *"    required>
            <input type="email"    name="email"     placeholder="Email *"     required>
            <input type="password" name="password"  placeholder="Mot de passe *" required>
            <input type="tel"      name="telephone" placeholder="Téléphone">
            <input type="text"     name="ville"     placeholder="Ville">
            <input type="text"     name="pays"      placeholder="Pays">
            <input type="text"     name="adresse_postale" placeholder="Adresse">
            <button type="submit">Ajouter</button>
        </form>
        <h2>Liste des employés</h2>
        <table>
            <thead><tr><th>Prénom</th><th>Email</th><th>Action</th></tr></thead>
            <tbody>
            ${employees.map(e => `
                <tr>
                    <td>${e.prenom}</td>
                    <td>${e.email}</td>
                    <td><button class="btn-del" data-id="${e.user_id}">Supprimer</button></td>
                </tr>
            `).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('form-emp').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            await createEmployee(Object.fromEntries(new FormData(e.target)));
            e.target.reset(); load();
        } catch (ex) { alert(ex.message); }
    });

    document.querySelectorAll('.btn-del').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Supprimer cet employé ?')) return;
            await deleteEmployee(btn.dataset.id); load();
        });
    });
}
load();
