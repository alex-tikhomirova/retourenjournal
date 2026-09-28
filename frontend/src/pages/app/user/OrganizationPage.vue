<script setup>
import {computed, ref} from 'vue'
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
const avvAcceptance = computed(() => orgStore.organization?.avv_acceptance)
const acceptedAt = computed(() => avvAcceptance.value
    ? new Date(avvAcceptance.value.accepted_at).toLocaleString('de-DE', {
      day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit',
    }).replace(',', '')
    : '')
const signedBy = computed(() => avvAcceptance.value?.user
    ? `${avvAcceptance.value.user.name} (${avvAcceptance.value.user.email})`
    : 'ehemaliger Nutzer')
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
    <PageCard class="padded" title="Rechtliche Dokumente">
      <div class="grid gap-12">
        <RouterLink to="/legal/avv" target="_blank">Auftragsverarbeitungsvertrag (AVV)</RouterLink>
        <RouterLink to="/legal/tom" target="_blank">Technische und organisatorische Maßnahmen (TOMs)</RouterLink>
        <RouterLink to="/legal/subprocessors" target="_blank">Unterauftragsverarbeiter</RouterLink>
        <RouterLink to="/legal/terms" target="_blank">Nutzungsbedingungen</RouterLink>
      </div>
      <div v-if="avvAcceptance" class="grid gap-12">
        <p>AVV abgeschlossen am: {{ acceptedAt }}</p>
        <p>Abgeschlossen durch: {{ signedBy }}</p>
        <p>Version: {{ avvAcceptance.document_version }}</p>
      </div>
      <p v-else>AVV noch nicht dokumentiert.</p>
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
