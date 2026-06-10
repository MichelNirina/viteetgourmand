import { renderNavbar } from '../components/navbar.js';
import { login } from '../services/api.js';
import { redirectIfLogged } from '../utils/auth.js';

renderNavbar();
redirectIfLogged();

const base = '/viteetgourmand/frontend';

document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    const err = document.getElementById('error');
    err.style.display = 'none';

    try {
        const data = await login(Object.fromEntries(new FormData(e.target)));
        if (data.role == 1)      window.location.href = `${base}/pages/admin/dashboard.html`;
        else if (data.role == 2) window.location.href = `${base}/pages/employee/dashboard.html`;
        else                     window.location.href = `${base}/pages/client/dashboard.html`;
    } catch (ex) {
        err.textContent = ex.message;
        err.style.display = 'block';
    }
});
