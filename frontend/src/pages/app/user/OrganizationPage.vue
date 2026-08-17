<script setup>
import {ref} from 'vue'
import {Save, X} from 'lucide-vue-next'
import PageCard from '@/components/PageCard.vue'
import ToolBar from '@/components/ToolBar.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import {useFormErrors} from '@/utils/useFormErrors.js'
import {useOrgStore} from '@/stores/org.js'
import {useRouter} from "vue-router";

const orgStore = useOrgStore()
const router = useRouter()
const saved = ref(false)
const formData = ref({name: orgStore.organization?.name ?? ''})
const {getError, hasError, clearError, setErrorsFromResponse} = useFormErrors()

const save = async () => {
  saved.value = false
  const result = await orgStore.updateOrganization(formData.value)
  if (!result.ok) {
    if (result.error.response?.status === 422) setErrorsFromResponse(result.error.response)
    return
  }
  saved.value = true
}
</script>

<template>
  <ToolBar title="Organisation" subtitle="Organisationsprofil verwalten">
  </ToolBar>

  <div class="settings-form-page container container-small grid gap-24 ">
    <PageCard class="padded" title="Organisationsdaten">
      <div class="settings-form-fields">
        <FormGroup name="name" label="Name der Organisation" :error="getError('name')" required>
          <FormFieldText v-model="formData.name" name="name" :invalid="hasError('name')" @update:modelValue="clearError('name')"/>
        </FormGroup>
      </div>
    </PageCard>
    <div class="flex justify-end gap-12 settings-form-actions">
      <button class="btn btn-outline-primary"  @click="router.back()"><X /> Abbrechen</button>
      <button class="btn btn-primary" :disabled="orgStore.isLoading" @click="save">
        <Save/>
        {{ orgStore.isLoading ? 'Wird gespeichert…' : 'Speichern' }}
      </button>
    </div>
  </div>
</template>
