import { renderLayout } from '../components/layout.js';
import { requireAuth } from '../utils/auth.js';
import { getProfile, updateProfil } from '../services/api.js';
import { esc } from '../utils/helpers.js';

renderLayout();
const app = document.getElementById('app');

async function load() {
    const user = await requireAuth();
    if (!user) return;

    const profil = await getProfile();

    app.innerHTML = `
        <h1>Mon profil</h1>
        <div id="msg" class="success-message" style="display:none"></div>
        <div id="err" class="error-message"   style="display:none"></div>
        <form id="form-profil" class="form-profil">
            <label>Prénom</label>
            <input type="text"  name="prenom"          value="${esc(profil.prenom          ?? '')}">
            <label>Email</label>
            <input type="email" name="email"            value="${esc(profil.email           ?? '')}">
            <label>Téléphone</label>
            <input type="tel"   name="telephone"        value="${esc(profil.telephone       ?? '')}">
            <label>Adresse</label>
            <input type="text"  name="adresse_postale"  value="${esc(profil.adresse_postale ?? '')}">
            <label>Ville</label>
            <input type="text"  name="ville"            value="${esc(profil.ville           ?? '')}">
            <label>Pays</label>
            <input type="text"  name="pays"             value="${esc(profil.pays            ?? '')}">
            <button type="submit">Enregistrer</button>
        </form>
    `;

    document.getElementById('form-profil').addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = document.getElementById('msg');
        const err = document.getElementById('err');
        msg.style.display = err.style.display = 'none';
        try {
            await updateProfil(Object.fromEntries(new FormData(e.target)));
            msg.textContent = 'Profil mis à jour !';
            msg.style.display = 'block';
        } catch (ex) {
            err.textContent = ex.message;
            err.style.display = 'block';
        }
    });
}

load().catch(err => {
    app.innerHTML = `<p class="error-message">Erreur : ${err.message}</p>`;
});
