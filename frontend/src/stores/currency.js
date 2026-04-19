import {defineStore} from "pinia";

export const useCurrencyStore = defineStore('currency', {
    state: () => ({
        activeCurrency: {code: 'EUR', symbol: '€'},
        rates: {
            'EUR': 1, //€
            'USD': 1.1805, //$
            'GBP': 0.87630, //£
        }
    }),
    getters: {
        toActive: (state) => (amount, currency = state.activeCurrency.code) => {
            const { rates, activeCurrency } = state
            return amount * (rates[activeCurrency.code] / rates[currency])
        },
        toActiveString: (state) => (amount, currency = state.activeCurrency.code) => {
            const { rates, activeCurrency } = state
            return (amount * (rates[activeCurrency.code] / rates[currency]) / 100).toFixed(2) + ` ${activeCurrency.symbol }`
        }
    },
})