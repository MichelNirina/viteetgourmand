import { fetchMenus, fetchMenu } from '../services/api.js';

const app = document.getElementById('app');

function renderMenuList(menus) {
    if (menus.length === 0) {
        app.innerHTML = '<p>Aucun menu disponible.</p>';
        return;
    }

    app.innerHTML = `
        <h1>Nos Menus</h1>
        <form id="filtres">
            <input type="number" name="prix_min" placeholder="Prix min">
            <input type="number" name="prix_max" placeholder="Prix max">
            <input type="number" name="personnes" placeholder="Nombre de personnes">
            <select name="theme">
                <option value="">Tous les thèmes</option>
                <option value="Noel">Noël</option>
                <option value="Pâques">Pâques</option>
                <option value="Classique">Classique</option>
            </select>
            <select name="regime">
                <option value="">Tous les régimes</option>
                <option value="Vegan">Vegan</option>
                <option value="Végétarien">Végétarien</option>
                <option value="Classique">Classique</option>
            </select>
            <button type="submit">Filtrer</button>
        </form>
        <div id="liste-menus">
            ${menus.map(menu => `
                <div class="card">
                    <h3>${menu.titre}</h3>
                    <p>${menu.description}</p>
                    <p>Prix : ${menu.prix_par_personne} € / personne</p>
                    <p>Personnes min : ${menu.nombre_personne}</p>
                    <p>Stock : ${menu.quantite_restante}</p>
                    <button class="btn" data-id="${menu.menu_id}">Voir détails</button>
                </div>
            `).join('')}
        </div>
    `;

    document.getElementById('filtres').addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target));
        const menus = await fetchMenus(data);
        renderMenuList(menus);
    });

    document.querySelectorAll('.btn[data-id]').forEach(btn => {
        btn.addEventListener('click', () => loadMenuDetail(btn.dataset.id));
    });
}

async function renderMenuDetail(menu) {
    const platsHtml = menu.plats.map(plat => `
        <div class="plat-card">
            <h3>${plat.titre_plat}</h3>
            ${plat.allergenes.length > 0
                ? `<p>Allergènes : ${plat.allergenes.map(a => a.libelle).join(', ')}</p>`
                : '<p>Aucun allergène</p>'
            }
        </div>
    `).join('');

    app.innerHTML = `
        <button id="retour">← Retour aux menus</button>
        <h1>${menu.titre}</h1>
        <p>${menu.description}</p>
        <p>Thème : ${menu.theme_libelle ?? 'Non défini'}</p>
        <p>Régime : ${menu.regime_libelle ?? 'Non défini'}</p>
        <p>Prix : ${menu.prix_par_personne} € / personne</p>
        <p>Personnes min : ${menu.nombre_personne}</p>
        <p>Stock : ${menu.quantite_restante}</p>
        <h2>Plats inclus</h2>
        <div class="plat-grid">${platsHtml}</div>
    `;

    document.getElementById('retour').addEventListener('click', loadMenuList);
}

async function loadMenuList() {
    const menus = await fetchMenus();
    renderMenuList(menus);
}

async function loadMenuDetail(id) {
    const menu = await fetchMenu(id);
    renderMenuDetail(menu);
}

loadMenuList();
