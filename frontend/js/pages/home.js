import { renderLayout } from '../components/layout.js';
import { getAvis } from '../services/api.js';
import { esc } from '../utils/helpers.js';

renderLayout();

const app = document.getElementById('app');

async function load() {
    const avis = await getAvis();

    app.innerHTML = `
        <div class="home-container">
            <h1>Bienvenue chez Vite &amp; Gourmand</h1>
            <p>Votre traiteur à Bordeaux depuis 25 ans.<br>
               Julie et José vous accompagnent pour tous vos événements.</p>
            <a href="pages/menu.html" class="btn">Découvrir nos menus</a>

            <hr>

            <h2>Notre professionnalisme</h2>

            <div class="card">
                <h3>Cuisine de qualité</h3>
                <p>Des menus savoureux préparés avec soin.</p>
            </div>
            <div class="card">
                <h3>Livraison rapide</h3>
                <p>Une équipe professionnelle et organisée.</p>
            </div>
            <div class="card">
                <h3>Événements</h3>
                <p>Mariages, anniversaires, Noël, Pâques.</p>
            </div>

            <hr>

            <h2>Avis clients</h2>
            ${avis.length === 0
                ? '<p>Aucun avis disponible.</p>'
                : avis.map(a => `
                    <div class="card">
                        <p>${esc(a.description)}</p>
                        <p>Note : ${esc(a.note)}/5</p>
                        <strong>${esc(a.prenom)}</strong>
                    </div>
                `).join('')
            }
        </div>
    `;
}

load().catch(() => {
    app.innerHTML = '<p class="error-message">Erreur de chargement.</p>';
});
