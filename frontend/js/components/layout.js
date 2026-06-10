import { renderNavbar } from './navbar.js';
import { renderFooter } from './footer.js';

export function renderLayout(containerId = 'navbar') {
    renderNavbar(containerId);
    renderFooter();
}
