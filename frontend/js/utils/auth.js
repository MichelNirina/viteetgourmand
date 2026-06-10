import { getMe } from '../services/api.js';

export async function getCurrentUser() {
    try {
        return await getMe();
    } catch {
        return null;
    }
}

export async function requireAuth(redirectTo = '/viteetgourmand/frontend/pages/login.html') {
    const user = await getCurrentUser();
    if (!user) {
        window.location.href = redirectTo;
        return null;
    }
    return user;
}

export async function requireRole(roles, redirectTo = '/viteetgourmand/frontend/index.html') {
    const user = await requireAuth();
    if (!user) return null;
    if (!roles.includes(user.role_id)) {
        window.location.href = redirectTo;
        return null;
    }
    return user;
}

export function redirectIfLogged(redirectTo = '/viteetgourmand/frontend/index.html') {
    getCurrentUser().then(user => {
        if (user) window.location.href = redirectTo;
    });
}
