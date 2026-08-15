import { defineStore } from 'pinia'
import { api } from '@/api/api'

/**
 * Find a lookup item by one of its fields.
 *
 * @param {Array<Record<string, unknown>>} items
 * @param {string} field
 * @param {unknown} value
 * @returns {Record<string, unknown>|null}
 */
const findBy = (items, field, value) => items.find(item => item[field] === value) ?? null

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
        returnStatus: (state) => (value, field = 'id') => findBy(state.returnStatuses, field, value),
        returnDecision: (state) => (value, field = 'id') => findBy(state.returnDecisions, field, value),
        shipmentStatus: (state) => (value, field = 'id') => findBy(state.shipmentStatuses, field, value),
        refundStatus: (state) => (value, field = 'id') => findBy(state.refundStatuses, field, value),

        initialReturnStatus: (state) => findBy(state.returnStatuses, 'kind', 1),
        initialShipmentStatus: (state) => findBy(state.shipmentStatuses, 'code', 'created'),
        initialRefundStatus: (state) => findBy(state.refundStatuses, 'code', 'pending'),
    }
})
