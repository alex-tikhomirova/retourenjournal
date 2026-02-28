import { defineStore } from 'pinia'
import { api } from '@/api/api'

const findById = (arr, id) => arr.find(x => x.id === id) ?? null
export const useLookupStore = defineStore('lookups', {
    state: () => ({
        returnStatuses: [],
        returnDecisions: [],
        shipmentStatuses: [],
        refundStatuses: [],
    }),

    actions: {
        reset() {
            this.returnStatuses = []
            this.returnDecisions = []
            this.shipmentStatuses = []
            this.refundStatuses = []
        },

        async fetchAll() {
            const { data } = await api.get('/api/lookups')
            this.returnStatuses = data?.return_statuses ?? []
            this.returnDecisions = data?.return_decisions ?? []
            this.shipmentStatuses = data?.shipment_statuses ?? []
            this.refundStatuses = data?.refund_statuses ?? []
        },
    },
    getters: {
        returnStatus: (state) => (id) => findById(state.returnStatuses, id),
        returnDecision: (state) => (id) => findById(state.returnDecisions, id),
        shipmentStatus: (state) => (id) => findById(state.shipmentStatuses, id),
        refundStatus: (state) => (id) => findById(state.refundStatuses, id),
    }
})