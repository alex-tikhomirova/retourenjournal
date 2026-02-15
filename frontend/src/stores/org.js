import { defineStore } from 'pinia'
import { api } from '@/api/api'

export const useOrgStore = defineStore('org', {
    state: () => ({
        organization: null, // null/false/object
        isLoading: false,
    }),

    getters: {
        hasOrganization: (s) => !!s.organization && s.organization !== false,
    },

    actions: {
        reset() {
            this.organization = null
            this.isLoading = false
        },

        async fetchOrganization({ force = false } = {}) {
            if (!force && this.organization !== null) return this.organization

            this.isLoading = true
            try {
                // TODO: замени на свой endpoint
                const { data } = await api.get('/api/organization')
                const org = data.organization ?? data.data ?? data ?? null
                this.organization = org ? org : false
                return this.organization
            } catch (e) {
                this.organization = false
                return this.organization
            } finally {
                this.isLoading = false
            }
        },
    },
})
