import { defineStore } from 'pinia'
import { api } from '@/api/api'

const findById = (arr, id) => arr.find(x => x.id === id) ?? null
export const useLookupStore = defineStore('lookups', {
    state: () => ({
        returnStatuses: [],
        returnDecisions: [],
        shipmentStatuses: [],
        refundStatuses: [],
        shipmentPayerOptions: [
            { value: 1, label: 'Kunde' },
            { value: 2, label: 'Händler' },
            { value: 3, label: 'Plattform / Marktplatz' },
            { value: 4, label: 'Geteilt (anteilig)' },
            { value: 5, label: 'Unbekannt' },
        ],
        shipmentCarrierOptions: [
            { value: 'DHL', label: 'DHL' },
            { value: 'DPD', label: 'DPD' },
            { value: 'Hermes', label: 'Hermes' },
            { value: 'UPS', label: 'UPS' },
            { value: 'Other', label: 'Other' },
        ],
        shipmentDirectionOptions: [
            { value: 1, label: 'Rücksendung vom Kunden' },
            { value: 2, label: 'Versand an den Kunden' },
        ]
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