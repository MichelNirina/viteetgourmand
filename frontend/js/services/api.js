const API = 'http://localhost/viteetgourmand/backend/public';

async function req(url, opts = {}) {
    const res = await fetch(API + url, { credentials: 'include', ...opts });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Erreur serveur');
    return data;
}

function post(url, body) {
    return req(url, { method: 'POST', body: new URLSearchParams(body) });
}

// ── AUTH ──────────────────────────────────────────────────────────────
export const getMe        = ()       => req('?page=auth');
export const login        = (b)      => post('?page=auth&action=login', b);
export const logout       = ()       => post('?page=auth&action=logout', {});
export const register     = (b)      => post('?page=register', b);

// ── PUBLIC ────────────────────────────────────────────────────────────
export const getAvis      = ()       => req('?page=home');
export const getFilters   = ()       => req('?page=filters');
export const sendContact  = (b)      => post('?page=contact', b);

// ── MENUS ─────────────────────────────────────────────────────────────
export const getMenus     = (f = {}) => req('?page=menu&' + new URLSearchParams(f));
export const getMenu      = (id)     => req(`?page=menu&action=show&id=${id}`);

// ── COMMANDES ─────────────────────────────────────────────────────────
export const getMesCommandes  = ()  => req('?page=commande&action=my');
export const getToutesCommandes = () => req('?page=commande');
export const createCommande   = (b) => post('?page=commande&action=store', b);
export const updateCommande   = (b) => post('?page=commande&action=update', b);
export const deleteCommande   = (id) => req(`?page=commande&action=delete&id=${id}`);
export const updateStatutEmploye = (b) => post('?page=commande&action=updateEmploye', b);

// ── AVIS ──────────────────────────────────────────────────────────────
export const getAllAvis   = ()       => req('?page=avis');
export const createAvis  = (b)      => post('?page=avis&action=store', b);
export const validerAvis = (id)     => req(`?page=avis&action=valider&id=${id}`);
export const refuserAvis = (id)     => req(`?page=avis&action=refuser&id=${id}`);
export const deleteAvis  = (id)     => req(`?page=avis&action=delete&id=${id}`);

// ── GESTION MENUS ─────────────────────────────────────────────────────
export const getMenusAdmin  = ()    => req('?page=gestion_menu');
export const createMenu     = (b)   => post('?page=gestion_menu&action=store', b);
export const updateMenu     = (b)   => post('?page=gestion_menu&action=update', b);
export const deleteMenu     = (id)  => req(`?page=gestion_menu&action=delete&id=${id}`);

// ── PLATS ─────────────────────────────────────────────────────────────
export const getPlats    = ()       => req('?page=plat');
export const getPlat     = (id)     => req(`?page=plat&action=show&id=${id}`);
export const deletePlat  = (id)     => req(`?page=plat&action=delete&id=${id}`);

// ── HORAIRES ──────────────────────────────────────────────────────────
export const getHoraires   = ()     => req('?page=horaire');
export const createHoraire = (b)    => post('?page=horaire&action=store', b);
export const updateHoraire = (b)    => post('?page=horaire&action=update', b);
export const deleteHoraire = (id)   => req(`?page=horaire&action=delete&id=${id}`);

// ── ALLERGÈNES ────────────────────────────────────────────────────────
export const getAllergenes   = ()   => req('?page=allergene');
export const createAllergene = (b)  => post('?page=allergene&action=store', b);
export const deleteAllergene = (id) => req(`?page=allergene&action=delete&id=${id}`);

// ── THÈMES ────────────────────────────────────────────────────────────
export const getThemes   = ()       => req('?page=theme');
export const createTheme = (b)      => post('?page=theme&action=store', b);
export const updateTheme = (b)      => post('?page=theme&action=update', b);
export const deleteTheme = (id)     => req(`?page=theme&action=delete&id=${id}`);

// ── RÉGIMES ───────────────────────────────────────────────────────────
export const getRegimes   = ()      => req('?page=regime');
export const createRegime = (b)     => post('?page=regime&action=store', b);
export const updateRegime = (b)     => post('?page=regime&action=update', b);
export const deleteRegime = (id)    => req(`?page=regime&action=delete&id=${id}`);

// ── UTILISATEURS ──────────────────────────────────────────────────────
export const getUsers    = ()       => req('?page=user');
export const getProfile  = ()       => req('?page=user&action=profile');
export const updateProfil = (b)     => post('?page=user&action=update', b);
export const deleteUser  = (id)     => req(`?page=user&action=delete&id=${id}`);

// ── ADMIN ─────────────────────────────────────────────────────────────
export const getStats       = ()    => req('?page=admin&action=stats');
export const getEmployees   = ()    => req('?page=admin&action=employees');
export const createEmployee = (b)   => post('?page=admin&action=storeEmployee', b);
export const deleteEmployee = (id)  => req(`?page=admin&action=deleteEmployee&id=${id}`);
