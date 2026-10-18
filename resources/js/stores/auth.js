import { authService } from '../services/auth';

export const authStore = {
    user: authService.user(),
    loading: false,
    error: null,

    async login(login, password) {
        this.loading = true;
        this.error = null;
        try { this.user = await authService.login(login, password); return this.user; }
        catch (error) { this.error = error.response?.data?.message || 'ورود ناموفق بود.'; throw error; }
        finally { this.loading = false; }
    },

    async logout() { await authService.logout(); this.user = null; },
    isAuthenticated() { return Boolean(authService.token()); },
};
