<script setup>
import {ref} from 'vue'
import {Save, X, CircleX} from 'lucide-vue-next'
import PageCard from '@/components/PageCard.vue'
import ToolBar from '@/components/ToolBar.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import {useFormErrors} from '@/utils/useFormErrors.js'
import {useUserStore} from '@/stores/user.js'
import {useRouter} from "vue-router";

const userStore = useUserStore()
const router = useRouter()
const saved = ref(false)
const formData = ref({
  name: userStore.user?.name ?? '',
  email: userStore.user?.email ?? '',
  password: '',
  password_confirmation: '',
})

const {getError, hasError, clearError, setErrorsFromResponse} = useFormErrors()

const save = async () => {
  saved.value = false
  const result = await userStore.updateProfile(formData.value)
  if (!result.ok) {
    if (result.error.response?.status === 422) setErrorsFromResponse(result.error.response)
    return
  }

  formData.value.password = ''
  formData.value.password_confirmation = ''
  saved.value = true
}
</script>

<template>
  <ToolBar title="Profil" subtitle="Persönliche Daten und Passwort verwalten">

  </ToolBar>

  <div class="settings-form-page container container-small grid gap-24 ">
    <PageCard class="padded" title="Persönliche Daten">
      <div class="settings-form-fields grid gap-12 ">
          <FormGroup name="name" class="flex-1" label="Name" :error="getError('name')" required>
            <FormFieldText v-model="formData.name" name="name" :invalid="hasError('name')"
                           @update:modelValue="clearError('name')"/>
          </FormGroup>
          <FormGroup name="email" class="flex-1" label="E-Mail-Adresse">
            <FormFieldText v-model="formData.email" name="email" type="email" disabled/>
          </FormGroup>


      </div>
    </PageCard>
    <PageCard class="padded" title="Passwort ändern">
      <div class="grid gap-12">
        <FormGroup name="password" class="flex-1" label="Neues Passwort" :error="getError('password')">
          <FormFieldText v-model="formData.password" name="password" type="password" autocomplete="new-password"
                         :invalid="hasError('password')" @update:modelValue="clearError('password')"/>
        </FormGroup>
        <FormGroup name="password_confirmation" class="flex-1" label="Passwort bestätigen">
          <FormFieldText v-model="formData.password_confirmation" name="password_confirmation" type="password"
                         autocomplete="new-password"/>
        </FormGroup>
      </div>
    </PageCard>
    <div class="flex justify-end gap-12 settings-form-actions">
      <button class="btn btn-outline-primary"  @click="router.back()"><X /> Abbrechen</button>
      <button class="btn btn-primary" :disabled="userStore.isLoading" @click="save">
        <Save/>
        {{ userStore.isLoading ? 'Wird gespeichert…' : 'Speichern' }}
      </button>
    </div>
    <PageCard class="padded" title="Konto löschen">
      <p>Konto und zugehörige Daten löschen</p>
      <div class="text-right">
        <button class="btn btn-danger">
          <CircleX/>
          Konto löschen
        </button>
      </div>
    </PageCard>


  </div>
</template>
