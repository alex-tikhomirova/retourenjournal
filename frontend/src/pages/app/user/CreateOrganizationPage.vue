<script setup>

import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
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

  const res = await org.createOrganization(formData.value)
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
        <button class="btn btn-primary" type="submit" :disabled="org.isLoading">
          <Check/> {{ org.isLoading ? 'Wird erstellt…' : 'Organisation erstellen' }}
        </button>

        <p v-if="getError('_general')" class="text-danger">{{ getError('_general') }}</p>
      </form>
    </PageCard>
  </div>
</template>
