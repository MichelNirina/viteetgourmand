const isProd = !['localhost', '127.0.0.1'].includes(window.location.hostname);
export const BASE = isProd ? '/frontend' : '/viteetgourmand/frontend';
