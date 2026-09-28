<script setup>

import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
import CheckBox from "@/components/forms/CheckBox.vue";
import {legalDocuments} from "@/content/legal";
import {ref} from "vue";
import {useOrgStore} from "@/stores/org.js";
import {useRouter} from "vue-router";
import PageCard from "@/components/PageCard.vue";
import {Check} from "lucide-vue-next";
import {useFormErrors} from "@/utils/useFormErrors.js";

const org = useOrgStore()
const router = useRouter()

const formData = ref({
  name: '',
  avv_accepted: false,
})

const {
  getError,
  hasError,
  clearError,
  clearErrors,
  setErrors,
  setErrorsFromResponse,
} = useFormErrors()

const onSubmit = async () => {
  clearErrors()

  if (!formData.value.name.trim()) {
    setErrors({name: ['Dieses Feld ist erforderlich.']})
    return
  }

  if (!formData.value.avv_accepted) {
    setErrors({avv_accepted: ['Bitte schließen Sie den AVV ab.']})
    return
  }

  const res = await org.createOrganization({
    name: formData.value.name,
    legal_acceptances: [{
      document_key: 'avv',
      document_version: legalDocuments.avv.version,
      document_hash: legalDocuments.avv.hash,
      action: 'contract_concluded',
    }],
  })
  if (!res.ok) {
    setErrorsFromResponse(res.error)
    return
  }

  await router.push('/app/returns')

}
</script>

<template>
  <div class="create-organization-page container container-small">
    <PageCard class="padded" title="Neue Organisation">
      <form class="create-organization-form grid gap-24" @submit.prevent="onSubmit">
        <FormGroup name="name" label="Name" :error="getError('name')" required>
          <FormFieldText
              v-model="formData.name"
              name="name"
              autocomplete="organization"
              :invalid="hasError('name')"
              @update:modelValue="clearError('name')"
          />
        </FormGroup>
        <div>
          <CheckBox v-model="formData.avv_accepted" @update:modelValue="clearError('avv_accepted')">
            Ich bin berechtigt, diese Organisation anzulegen und schließe den
            <RouterLink to="/legal/avv" target="_blank">Auftragsverarbeitungsvertrag (AVV)</RouterLink> ab.
          </CheckBox>
          <p class="hint">
            Der AVV umfasst die
            <RouterLink to="/legal/tom" target="_blank">Technischen und organisatorischen Maßnahmen (TOMs)</RouterLink>
            sowie die <RouterLink to="/legal/subprocessors" target="_blank">Unterauftragsverarbeiter</RouterLink>.
          </p>
          <p v-if="getError('avv_accepted')" class="text-danger">{{ getError('avv_accepted') }}</p>
        </div>
        <button class="btn btn-primary" type="submit" :disabled="org.isLoading">
          <Check/> {{ org.isLoading ? 'Wird erstellt…' : 'Organisation erstellen' }}
        </button>

        <p v-if="getError('_general')" class="text-danger">{{ getError('_general') }}</p>
      </form>
    </PageCard>
  </div>
</template>
