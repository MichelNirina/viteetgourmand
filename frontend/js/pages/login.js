import { renderLayout } from '../components/layout.js';
import { login } from '../services/api.js';
import { redirectIfLogged } from '../utils/auth.js';
import { BASE } from '../utils/config.js';

renderLayout();
redirectIfLogged();

document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    const err = document.getElementById('error');
    err.style.display = 'none';

    try {
        const data = await login(Object.fromEntries(new FormData(e.target)));
        if (data.role == 1)      window.location.href = `${BASE}/pages/admin/dashboard.html`;
        else if (data.role == 2) window.location.href = `${BASE}/pages/employee/dashboard.html`;
        else                     window.location.href = `${BASE}/pages/client/dashboard.html`;
    } catch (ex) {
        err.textContent = ex.message;
        err.style.display = 'block';
    }
});
