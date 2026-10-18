import api from './api';

const TOKEN_KEY = 'setareh_shab_token';
const USER_KEY = 'setareh_shab_user';

export const authService = {
    async login(login, password) {
        const { data } = await api.post('/auth/login', { login, password });
        localStorage.setItem(TOKEN_KEY, data.data.token);
        localStorage.setItem(USER_KEY, JSON.stringify(data.data.user));
        return data.data.user;
    },

    async me() {
        const { data } = await api.get('/auth/me');
        localStorage.setItem(USER_KEY, JSON.stringify(data.data));
        return data.data;
    },

    async logout() {
        try { await api.post('/auth/logout'); } finally { this.clear(); }
    },

    async refresh() {
        const { data } = await api.post('/auth/refresh');
        localStorage.setItem(TOKEN_KEY, data.data.token);
        return data.data.token;
    },

    token() { return localStorage.getItem(TOKEN_KEY); },
    user() { return JSON.parse(localStorage.getItem(USER_KEY) || 'null'); },
    clear() { localStorage.removeItem(TOKEN_KEY); localStorage.removeItem(USER_KEY); },
};
