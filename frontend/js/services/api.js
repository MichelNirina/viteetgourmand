const API_BASE = 'http://localhost/viteetgourmand/backend/public';

export async function fetchMenus(filters = {}) {
    const params = new URLSearchParams({ page: 'menu', ...filters });
    const res = await fetch(`${API_BASE}?${params}`);
    if (!res.ok) throw new Error('Erreur lors du chargement des menus');
    return res.json();
}

export async function fetchMenu(id) {
    const res = await fetch(`${API_BASE}?page=menu&action=show&id=${id}`);
    if (!res.ok) throw new Error('Menu introuvable');
    return res.json();
}

export async function fetchFilters() {
    const res = await fetch(`${API_BASE}?page=filters`);
    if (!res.ok) throw new Error('Erreur lors du chargement des filtres');
    return res.json();
}
