import { defineStore } from 'pinia'
import { api } from '@/api/api'
import {useUserStore} from "@/stores/user.js";
import {useLookupStore} from "@/stores/lookups.js";

export const useOrgStore = defineStore('org', {
    state: () => ({
        organization: null, // null/false/object
        isLoading: false,
        createPromise: null,
    }),

    getters: {
        hasOrganization: (s) => !!s.organization && s.organization !== false,
    },

    actions: {
        reset() {
            this.organization = null
            this.isLoading = false
            this.createPromise = null
        },

        async fetchOrganization({ force = false } = {}) {
            if (!force && this.organization !== null) {
                return this.organization
            }

            this.isLoading = true
            try {
                const { data } = await api.get('/api/organization')
                const org = data.organization ?? data.data ?? data ?? null
                this.organization = org ? org : false
                if (this.organization){
                    const lookups = useLookupStore()
                    await lookups.fetchAll()
                }
                return this.organization
            } catch (e) {
                this.organization = false
                return this.organization
            } finally {
                this.isLoading = false
            }
        },
        async createOrganization(payload, { refreshUser = true } = {}) {
            // double click secure
            if (this.createPromise) {
                return this.createPromise
            }
            this.isLoading = true
            this.createPromise = (async () => {
                try {
                    const { data } = await api.post('/api/organization', payload)

                    const org =  data.data ?? null
                    this.organization = org ? org : false

                    if (refreshUser) {
                        const user = useUserStore()
                        await user.fetchUser({ force: true }) // to get current_organization_id
                    }

                    return { ok: true, organization: this.organization }
                } catch (error) {
                    return { ok: false, error }
                } finally {
                    this.isLoading = false
                    this.createPromise = null
                }
            })()

            return this.createPromise
        }
    },
})
