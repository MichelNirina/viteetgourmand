import { renderNavbar } from '../components/navbar.js';
import { getAvis } from '../services/api.js';

renderNavbar();

const app = document.getElementById('app');

getAvis().then(avis => {
    app.innerHTML = `
        <div class="home-container">
            <h1>Bienvenue chez Vite & Gourmand</h1>
            <p>Votre traiteur à Bordeaux depuis 25 ans.</p>
            <a href="pages/menu.html" class="btn">Découvrir nos menus</a>
            <hr>
            <h2>Avis clients</h2>
            ${avis.length === 0
                ? '<p>Aucun avis disponible.</p>'
                : avis.map(a => `
                    <div class="card">
                        <p>${a.description}</p>
                        <p>Note : ${a.note}/5</p>
                        <strong>${a.prenom}</strong>
                    </div>
                `).join('')
            }
        </div>
    `;
}).catch(() => {
    app.innerHTML = '<p>Erreur de chargement.</p>';
});
