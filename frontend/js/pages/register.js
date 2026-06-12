import { renderLayout } from '../components/layout.js';
import { register } from '../services/api.js';

renderLayout();

document.getElementById('form-register').addEventListener('submit', async (e) => {
    e.preventDefault();
    const err = document.getElementById('error');
    const ok  = document.getElementById('success');
    err.style.display = ok.style.display = 'none';

    try {
        await register(Object.fromEntries(new FormData(e.target)));
        ok.textContent = 'Compte créé ! Vous pouvez vous connecter.';
        ok.style.display = 'block';
        e.target.reset();
        setTimeout(() => window.location.href = 'login.html', 1500);
    } catch (ex) {
        err.textContent = ex.message;
        err.style.display = 'block';
    }
});
