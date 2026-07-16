<script setup>
import {ref} from "vue";
import PageCard from "@/components/PageCard.vue";
import {api} from "@/api/api.js";
import {debounce} from "@/utils/debounce.js";
import SuggestedCustomersModal from "@/pages/app/return-new/SuggestedCustomersModal.vue";
import CustomerFormFields from "@/components/forms/CustomerFormFields.vue";

const {
  getError,
  hasError,
  clearError,
} = defineProps({
  getError: Function,
  hasError: Function,
  clearError: Function,
})

const customer = defineModel({
  type: Object,
  default: () => ({
    id: '',
    name: '',
    email: '',
    phone: '',
    address_text: '',
  }),
})

const suggestedModal = ref(false)
const suggestedCustomers = ref([])

const isSearchableEmail = (email) => {
  const value = (email ?? '').trim()
  const atIndex = value.indexOf('@')

  return value.length >= 6 && atIndex !== -1 && value.slice(atIndex + 1).length >= 2
}

const normalizePhone = (phone) => (phone ?? '').replace(/\D/g, '')

const userSearch = debounce(async () => {
  const email = (customer.value.email ?? '').trim()
  const phone = normalizePhone(customer.value.phone)
  const searchCustomer = {}

  if (isSearchableEmail(email)) {
    searchCustomer.email = email
  }

  if (phone.length >= 7) {
    searchCustomer.phone = phone
  }

  if (!Object.keys(searchCustomer).length) {
    suggestedCustomers.value = []
    suggestedModal.value = false
    return
  }

  const data = await api.post('/api/customers/search', searchCustomer)
  suggestedCustomers.value = data.data.data ?? []
}, 1000)

const setCustomer = (selectedCustomer) => {
  customer.value = selectedCustomer
  suggestedModal.value = false
  suggestedCustomers.value = []
}

const onCustomerChange = (field) => {
  if (['name', 'email', 'phone'].includes(field)){
    customer.value.id = ''
  }
  if (['email', 'phone'].includes(field)){
    userSearch()
  }

}

</script>

<template>
  <PageCard class="return-customer-form padded" title="Kunde">
    <template #title>
      <a href="#" class="text-small text-muted" v-if="suggestedCustomers.length"
         @click.prevent="() => suggestedModal = true">
        Ähnliche Kunden gefunden ({{ suggestedCustomers.length }})
      </a>
      <span v-else-if="customer.id">
        Bestehender Kunde [<a href="#" @click.prevent="() => onCustomerChange('phone')">Ändern</a>]
      </span>
    </template>
    <SuggestedCustomersModal
        v-if="suggestedModal"
        @close="suggestedModal = false"
        @useCustomer="setCustomer"
        :suggestedCustomers="suggestedCustomers"
    />
    <CustomerFormFields
        v-model="customer"
        @change="onCustomerChange"
        :get-error="getError"
        :has-error="hasError"
        :clear-error="clearError"
    />
  </PageCard>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.return-customer-form {
  flex: 1 1 calc((100% - #{variables.$module-gap}) / 2);
  min-width: 0;
  @media (max-width: 768px) {
    flex-basis: 100%;
  }

}
</style>
