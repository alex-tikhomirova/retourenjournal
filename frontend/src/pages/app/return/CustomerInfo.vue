<script setup>

import PageCard from "@/components/PageCard.vue";
import {Pencil, Save, X} from "lucide-vue-next";
import {ref} from "vue";
import CustomerFormFields from "@/components/forms/CustomerFormFields.vue";
import {useFormErrors} from "@/utils/useFormErrors.js";
import {api} from "@/api/api.js";

const customer = defineModel({
  type: Object,
  required: true,
})

defineProps({
  editable: {
    type: Boolean,
    default: true,
  }
})

const editMode = ref(false)

const {
  getError,
  hasError,
  clearError,
  setErrorsFromResponse,
} = useFormErrors()
const save = async () => {
  try{
    const {data} = await api.patch(`/api/customers/${customer.value.id}`, {customer: customer.value})
    if (data?.data?.id ?? null) {
      editMode.value = false
    }
  }catch (error){
    if (error.response?.status === 422) {
      setErrorsFromResponse(error.response)
      return
    }
    throw error
  }
}

</script>

<template>
  <PageCard title="Kunde" class="block-customer" :class="{'padded': editMode}">
    <template #title>
      <a href="#" class="btn btn-sm btn-link" @click.prevent="editMode = !editMode">
        <template v-if="editable && !editMode"><Pencil />Bearbeiten</template>

      </a>
    </template>
    <div class="customer-info">
        <div class="customer-form flex flex-col gap-24 items-end" v-if="editMode">
          <CustomerFormFields
              v-model="customer"
              :get-error="getError"
              :has-error="hasError"
              :clear-error="clearError"
          />
          <div class="controls flex gap-12 justify-end">
            <button class="btn btn-outline-primary btn-sm" @click="editMode = false">
              <X/>
              Abbrechen
            </button>
            <button class="btn btn-primary btn-sm" @click="save">
              <Save/>
              Speichern
            </button>
          </div>
        </div>
        <table class="table" v-else>
          <tbody>
          <tr>
            <td>Name:</td>
            <td>{{ customer.name }}</td>
          </tr>
          <tr>
            <td>Telefon:</td>
            <td>{{ customer.phone ?? '—' }}</td>
          </tr>
          <tr>
            <td>E-mail:</td>
            <td>{{ customer.email ?? '—' }}</td>
          </tr>
          <tr>
            <td>Adresse:</td>
            <td>{{ customer.address_text ?? '—'}}</td>
          </tr>
          </tbody>
        </table>
    </div>
  </PageCard>
</template>

<style scoped lang="scss">

</style>