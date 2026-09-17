<script setup>
import {ref} from 'vue'
import {Save, X, CircleX} from 'lucide-vue-next'
import PageCard from '@/components/PageCard.vue'
import Modal from '@/components/Modal.vue'
import ToolBar from '@/components/ToolBar.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import {useFormErrors} from '@/utils/useFormErrors.js'
import {useUserStore} from '@/stores/user.js'
import {useRouter} from "vue-router";

const userStore = useUserStore()
const router = useRouter()
const saved = ref(false)
const deleteModalOpen = ref(false)
const deletePassword = ref('')
const deleteError = ref('')
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

const openDeleteModal = () => {
  deletePassword.value = ''
  deleteError.value = ''
  deleteModalOpen.value = true
}

const closeDeleteModal = () => {
  if (userStore.isLoading) return

  deleteModalOpen.value = false
  deletePassword.value = ''
  deleteError.value = ''
}

const deleteAccount = async () => {
  if (userStore.isLoading) return

  deleteError.value = ''
  const result = await userStore.deleteProfile(deletePassword.value)

  if (!result.ok) {
    deleteError.value = result.error.response?.status === 409
        ? result.error.response.data?.message
        : 'Das Konto konnte nicht gelöscht werden. Bitte prüfen Sie Ihr Passwort und versuchen Sie es erneut.'
    return
  }

  await router.push('/')
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
    <section class="delete-account-section grid gap-12">
      <h3>Konto löschen</h3>
      <p>
        Wenn Sie Ihr Konto löschen, wird Ihr Konto dauerhaft entfernt. Wenn Sie Eigentümer der aktuellen Organisation
        sind, werden auch die Organisation und alle zugehörigen Retouren, Kunden, Sendungen, Erstattungen und
        Verlaufsdaten gelöscht. Diese Aktion kann nicht rückgängig gemacht werden.
      </p>
      <div class="text-right">
        <button class="btn btn-danger" @click="openDeleteModal">
          <CircleX/>
          Konto löschen
        </button>
      </div>
    </section>


  </div>

  <Modal
      v-if="deleteModalOpen"
      title="Konto wirklich löschen?"
      size="sm"
      :dismissable="!userStore.isLoading"
      @close="closeDeleteModal"
  >
    <div class="grid gap-20">
      <p>
        Diese Aktion löscht Ihr Konto dauerhaft. Wenn Sie Eigentümer der aktuellen Organisation sind, werden auch die
        Organisation und alle zugehörigen Retouren, Kundendaten, Sendungen, Erstattungen und Verlaufsdaten gelöscht.
      </p>
      <FormGroup name="delete_password" label="Passwort" :error="deleteError">
        <FormFieldText
            v-model="deletePassword"
            name="delete_password"
            type="password"
            autocomplete="current-password"
            :invalid="!!deleteError"
            @update:modelValue="deleteError = ''"
            @keyup.enter="deleteAccount"
        />
      </FormGroup>
    </div>
    <template #footer>
      <div class="flex justify-end gap-12">
        <button class="btn btn-outline-primary" :disabled="userStore.isLoading" @click="closeDeleteModal">
          Abbrechen
        </button>
        <button class="btn btn-danger" :disabled="userStore.isLoading" @click="deleteAccount">
          {{ userStore.isLoading ? 'Konto wird gelöscht…' : 'Konto endgültig löschen' }}
        </button>
      </div>
    </template>
  </Modal>
</template>
