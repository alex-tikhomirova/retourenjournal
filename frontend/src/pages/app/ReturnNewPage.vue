<script setup>

import PageCard from "@/components/PageCard.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import {ref} from "vue";
import {api} from "@/api/api.js";
import {useRouter} from "vue-router";
import {X, Save} from "lucide-vue-next";
import ToolBar from "@/components/ToolBar.vue";
import FormFieldTextArea from "@/components/forms/FormFieldTextArea.vue";
import {useFormErrors} from "@/utils/useFormErrors.js";
import {useLookupStore} from "@/stores/lookups.js";
import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
import ReturnItemsForm from "@/pages/app/return-new/ReturnItemsForm.vue";
import ReturnCustomerForm from "@/pages/app/return-new/ReturnCustomerForm.vue";

const router = useRouter()

const formData = ref({
  return_number: '',
  order_reference: '',
  reason: '',
  customer: {
    id: '',
    name: '',
    email: '',
    phone: '',
    address_text: '',
  },
  items: [],
})

const {
  getError,
  hasError,
  clearError,
  setErrorsFromResponse,
} = useFormErrors()

api.get('api/returns/next-number').then(res => formData.value.return_number = res.data?.data?.return_number ?? '')

const lookup = useLookupStore()
const initialStatus = lookup.returnStatuses.reduce((initial, status) => (status.kind !== 1 ? initial : status), null)

const save = async () => {
  try {
    const {data} = await api.post('/api/returns/store', formData.value);
    if (data?.data?.id ?? null) {
      await router.push(`/app/returns/${data.data.id}`)
    }
  } catch (error) {
    if (error.response?.status === 422) {
      setErrorsFromResponse(error.response)
      return
    }
    throw error
  }

}

</script>

<template>

  <ToolBar :on-back="() => router.push('/app/returns')" title="Neue Retoure erstellen"
           subtitle="Erfassen Sie die Retourendaten">
    <template #right>
      <button class="btn btn-outline-primary" @click="router.push('/app/returns')">
        <X/>
        Abbrechen
      </button>
      <button class="btn btn-primary" @click="save">
        <Save/>
        Speichern
      </button>
    </template>
  </ToolBar>
  <div class="return-form-page container">

    <PageCard class="return padded" title="Retourdaten">
      <template #title>
        <ReturnStatusLabel :status="initialStatus"/>
      </template>
      <div class="flex gap-24">
        <FormGroup name="return_number" label="Retourennummer" class="flex-1" :error="getError('return_number')"
                   required>
          <FormFieldText
              v-model="formData.return_number"
              name="return_number"
              placeholder="z.B. RET-69C7BD7B"
              :invalid="hasError('return_number')"
              @update:modelValue="() => clearError('return_number')"
          />
        </FormGroup>
        <FormGroup name="order_reference" label="Bestellnummer / Referenz" class="flex-1">
          <FormFieldText v-model="formData.order_reference" name="order_reference" placeholder="z.B. ORD-12345"/>
        </FormGroup>
      </div>
    </PageCard>

    <ReturnCustomerForm
        v-model="formData.customer"
        :get-error="getError"
        :has-error="hasError"
        :clear-error="clearError"
    />

    <PageCard class="return-reason padded" title="Rücksendegrund">

      <FormFieldTextArea v-model="formData.reason" name="reason" rows="3"
                         placeholder="Begründen Sie die Rücksendung ..."/>

    </PageCard>

    <ReturnItemsForm
        v-model="formData.items"
        :get-error="getError"
        :has-error="hasError"
        :clear-error="clearError"
    />

    <div class="flex gap-24 flex-1 justify-end">
      <button class="btn btn-outline-primary" @click="router.push('/app/returns')">
        <X/>
        Abbrechen
      </button>
      <button class="btn btn-primary" @click="save">
        <Save/>
        Retoure speichern
      </button>
    </div>

  </div>
</template>

<style lang="scss">
@use "@/assets/scss/variables";

.return-form-page {
  display: flex;
  flex-wrap: wrap;
  gap: variables.$module-gap;

  .return {
    flex: 1 1 calc((100% - #{variables.$module-gap}) / 2);
    min-width: 0;
    @media (max-width: 768px) {
      flex-basis: 100%;
    }
  }

  .return-reason {
    flex: 1 1 100%;
  }
}
</style>