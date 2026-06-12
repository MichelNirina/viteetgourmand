import { renderLayout } from '../components/layout.js';
import { getMenus, getMenu, getFilters } from '../services/api.js';
import { esc } from '../utils/helpers.js';
import { BASE } from '../utils/config.js';

renderLayout();

const app = document.getElementById('app');

function buildOptions(values, label) {
    return `<option value="">Tous les ${label}</option>` +
        values.map(v => `<option value="${esc(v)}">${esc(v)}</option>`).join('');
}

function renderMenuList(menus, filters, activeFilters = {}) {
    app.innerHTML = `
        <h1>Nos Menus</h1>
        <form id="filtres">
            <input type="number" name="prix_min" placeholder="Prix min"
                value="${esc(activeFilters.prix_min ?? '')}">
            <input type="number" name="prix_max" placeholder="Prix max"
                value="${esc(activeFilters.prix_max ?? '')}">
            <input type="number" name="personnes" placeholder="Nombre de personnes"
                value="${esc(activeFilters.personnes ?? '')}">
            <select name="theme">
                ${buildOptions(filters.themes, 'thèmes')}
            </select>
            <select name="regime">
                ${buildOptions(filters.regimes, 'régimes')}
            </select>
            <button type="submit">Filtrer</button>
        </form>
        <div id="liste-menus">
            ${menus.length === 0
                ? '<p>Aucun menu disponible.</p>'
                : menus.map(menu => `
                    <div class="card">
                        <h3>${esc(menu.titre)}</h3>
                        <p>${esc(menu.description)}</p>
                        <p>Prix : ${esc(menu.prix_par_personne)} € / personne</p>
                        <p>Personnes min : ${esc(menu.nombre_personne)}</p>
                        <p>Stock : ${esc(menu.quantite_restante)}</p>
                        <button class="btn-detail btn" data-id="${esc(menu.menu_id)}">Voir détails</button>
                    </div>
                `).join('')
            }
        </div>
    `;

    if (activeFilters.theme) {
        document.querySelector('select[name="theme"]').value = activeFilters.theme;
    }
    if (activeFilters.regime) {
        document.querySelector('select[name="regime"]').value = activeFilters.regime;
    }

    document.getElementById('filtres').addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(
            [...new FormData(e.target)].filter(([, v]) => v !== '')
        );
        const menus = await getMenus(data);
        renderMenuList(menus, filters, data);
    });

    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', () => loadMenuDetail(btn.dataset.id, filters));
    });
}

function renderMenuDetail(menu, filters) {
    const platsHtml = (menu.plats ?? []).map(plat => `
        <div class="plat-card">
            ${plat.photo
                ? `<img src="${BASE}/${esc(plat.photo)}" alt="${esc(plat.titre_plat)}" class="plat-img">`
                : '<div class="plat-img plat-img--placeholder"></div>'
            }
            <h3>${esc(plat.titre_plat)}</h3>
            ${plat.allergenes && plat.allergenes.length > 0
                ? `<p>Allergènes : ${plat.allergenes.map(a => esc(a.libelle)).join(', ')}</p>`
                : '<p>Aucun allergène</p>'
            }
        </div>
    `).join('');

    app.innerHTML = `
        <button id="retour" class="btn">← Retour aux menus</button>
        <h1>${esc(menu.titre)}</h1>
        <p>${esc(menu.description)}</p>
        <p>Thème : ${esc(menu.theme_libelle ?? 'Non défini')}</p>
        <p>Régime : ${esc(menu.regime_libelle ?? 'Non défini')}</p>
        <p>Prix : ${esc(menu.prix_par_personne)} € / personne</p>
        <p>Personnes min : ${esc(menu.nombre_personne)}</p>
        <p>Stock : ${esc(menu.quantite_restante)}</p>
        ${menu.quantite_restante > 0
            ? `<a href="${BASE}/pages/commande/create.html?menu_id=${esc(menu.menu_id)}" class="btn">Commander ce menu</a>`
            : '<p class="error"><strong>Ce menu n\'est plus disponible.</strong></p>'
        }
        <h2>Plats inclus</h2>
        <div class="plat-grid">${platsHtml || '<p>Aucun plat.</p>'}</div>
    `;

    document.getElementById('retour').addEventListener('click', () => loadMenuList(filters));
}

async function loadMenuList(filters = null) {
    if (!filters) filters = await getFilters();
    const menus = await getMenus();
    renderMenuList(menus, filters);
}

async function loadMenuDetail(id, filters) {
    const menu = await getMenu(id);
    renderMenuDetail(menu, filters);
}

loadMenuList().catch(() => {
    app.innerHTML = '<p class="error-message">Erreur de chargement des menus.</p>';
});
