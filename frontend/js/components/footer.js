import { getHorairesPublic } from '../services/api.js';
import { BASE } from '../utils/config.js';

export async function renderFooter() {
    if (document.getElementById('site-footer')) return;

    let horaireHtml = '';
    try {
        const horaires = await getHorairesPublic();
        horaireHtml = horaires.map(h => `
            <p><strong>${h.jour}</strong> : ${h.creneaux}</p>
        `).join('');
    } catch {
        horaireHtml = '<p>Horaires non disponibles</p>';
    }

    const footer = document.createElement('footer');
    footer.id = 'site-footer';
    footer.className = 'footer';
    footer.innerHTML = `
        <div class="footer-columns">
            <div class="footer-col">
                <p><strong>Horaires</strong></p>
                ${horaireHtml}
            </div>
            <div class="footer-col footer-col-links">
                <a href="${BASE}/pages/mentions.html">Mentions légales</a>
                <a href="${BASE}/pages/cgv.html">Conditions générales de vente</a>
            </div>
        </div>
        <p class="footer-copy">&copy; ${new Date().getFullYear()} Vite &amp; Gourmand - Tous droits réservés</p>
    `;
    document.body.appendChild(footer);
}
