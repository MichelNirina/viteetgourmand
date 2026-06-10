import { getMe } from '../services/api.js';
import { BASE } from './config.js';

export async function getCurrentUser() {
    try {
        return await getMe();
    } catch {
        return null;
    }
}

export async function requireAuth(redirectTo = `${BASE}/pages/login.html`) {
    const user = await getCurrentUser();
    if (!user) {
        window.location.href = redirectTo;
        return null;
    }
    return user;
}

export async function requireRole(roles, redirectTo = `${BASE}/index.html`) {
    const user = await requireAuth();
    if (!user) return null;
    if (!roles.includes(Number(user.role_id))) {
        window.location.href = redirectTo;
        return null;
    }
    return user;
}

export function redirectIfLogged(redirectTo = `${BASE}/index.html`) {
    getCurrentUser().then(user => {
        if (user) window.location.href = redirectTo;
    });
}
