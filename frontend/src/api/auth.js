import { api } from './api'

export const auth = {
    register(payload) {
        return api.post('/api/auth/register', payload).then(r => r.data)
    },

    login(payload) {
        return api.post('/api/auth/login', payload).then(r => r.data)
    },

    me() {
        return api.get('/api/auth/me').then(r => r.data)
    },

    logout() {
        return api.post('/api/auth/logout').then(r => r.data)
    },

    // optional:
    resendVerification() {
        return api.post('/api/auth/email/verification-notification').then(r => r.data)
    },

    verifyEmail(urlPath) {
        return api.get(urlPath).then(r => r.data)
    },

    forgotPassword(payload) {
        return api.post('/api/auth/forgot-password', payload).then(r => r.data)
    },

    resetPassword(payload) {
        return api.post('/api/auth/reset-password', payload).then(r => r.data)
    },
}
