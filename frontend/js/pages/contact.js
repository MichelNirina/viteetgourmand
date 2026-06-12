import { renderLayout } from '../components/layout.js';
import { sendContact } from '../services/api.js';

renderLayout();

document.getElementById('form-contact').addEventListener('submit', async (e) => {
    e.preventDefault();
    const err = document.getElementById('error');
    const ok  = document.getElementById('success');
    err.style.display = ok.style.display = 'none';

    try {
        await sendContact(Object.fromEntries(new FormData(e.target)));
        ok.textContent = 'Message envoyé avec succès !';
        ok.style.display = 'block';
        e.target.reset();
    } catch (ex) {
        err.textContent = ex.message;
        err.style.display = 'block';
    }
});
