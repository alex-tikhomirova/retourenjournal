import { defineStore } from 'pinia'
import { auth } from '@/api/auth'
import { useOrgStore } from '@/stores/org.js'

export const useUserStore = defineStore('user', {
    state: () => ({
        // null = не загружали, false = не залогинен, object = user ok
        user: null,
        isLoading: false,
        mePromise: null,   // <-- dedup
    }),

    getters: {
        isLoggedIn: (s) => !!s.user && s.user !== false,
        isBootstrapped: (s) => s.user !== null,
        isVerified: (s) => !!s.user && s.user !== false && !!s.user.is_verified,
        hasOrganization: (s) =>
            !!s.user && s.user !== false && !!s.user.current_organization_id,
        needsOrgOnboarding() {
            return this.isLoggedIn && this.isVerified && !this.hasOrganization
        },
    },

    actions: {
        reset() {
            this.user = null
            this.isLoading = false

            // важно: сбросить org, чтобы не было “призраков”
            const org = useOrgStore()
            org.reset()
        },



        async fetchUser({ force = false } = {}) {
            if (!force && this.user !== null) {
                return this.user
            }
            //dedup - weit a promise
            if (!force && this.mePromise) {
                return this.mePromise
            }
            this.isLoading = true
            this.mePromise = (async () => {
                try {
                    const data = await auth.me()
                    this.user = data.user ?? false

                    // если не залогинен — гарантированно чистим org
                    if (this.user === false) {
                        const org = useOrgStore()
                        org.reset()
                    }

                    return this.user
                } catch (e) {
                    this.user = false
                    const org = useOrgStore()
                    org.reset()
                    return this.user
                } finally {
                    this.isLoading = false
                    this.mePromise = null
                }
            })()

        },

        async login(email, password) {
            this.isLoading = true
            const org = useOrgStore()

            try {
                const data = await auth.login({ email, password })
                this.user = data.user

                // после логина нужно заново определить org
                org.reset()
                await org.fetchOrganization({ force: true })

                return { ok: true }
            } catch (error) {
                this.user = false

                // на неуспешном логине тоже чистим org
                org.reset()

                return { ok: false, error }
            } finally {
                this.isLoading = false
            }
        },

        async register({ name, email, password, password_confirmation }) {
            this.isLoading = true
            const org = useOrgStore()

            try {
                const data = await auth.register({ name, email, password, password_confirmation })
                this.user = data.user

                // после регистрации также пересобираем org состояние
                org.reset()
                await org.fetchOrganization({ force: true })

                return { ok: true }
            } catch (error) {
                this.user = false
                org.reset()
                return { ok: false, error }
            } finally {
                this.isLoading = false
            }
        },

        async logout() {
            this.isLoading = true
            const org = useOrgStore()

            try {
                await auth.logout()
            } catch (e) {
                // игнор, но локально всё равно разлогиниваем
            } finally {
                this.user = false
                org.reset()
                this.isLoading = false
            }
        },
    },
})
