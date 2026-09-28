<script setup>
import {ref} from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user.js'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import CheckBox from '@/components/forms/CheckBox.vue'
import {legalDocuments} from '@/content/legal'
import {useFormErrors} from '@/utils/useFormErrors.js'

const router = useRouter()
const user = useUserStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms_accepted: false,
  privacy_acknowledged: false,
})

const {
  getError,
  hasError,
  clearError,
  clearErrors,
  setErrors,
  setErrorsFromResponse,
} = useFormErrors()

const redirectAuthedHome = () => {
  if (!user.isVerified) return router.push('/app/email-not-verified')
  if (!user.user?.current_organization_id) return router.push('/app/welcome')
  return router.push('/app/returns')
}

const onSubmit = async () => {
  clearErrors()

  const requiredErrors = {}
  for (const field of ['name', 'email', 'password', 'password_confirmation']) {
    if (!form.value[field]) requiredErrors[field] = ['Dieses Feld ist erforderlich.']
  }
  if (!form.value.terms_accepted) requiredErrors.terms_accepted = ['Bitte akzeptieren Sie die Nutzungsbedingungen.']
  if (!form.value.privacy_acknowledged) requiredErrors.privacy_acknowledged = ['Bitte bestätigen Sie die Kenntnisnahme der Datenschutzerklärung.']
  if (Object.keys(requiredErrors).length) {
    setErrors(requiredErrors)
    return
  }

  if (form.value.password !== form.value.password_confirmation) {
    setErrors({password_confirmation: ['Die Passwörter stimmen nicht überein.']})
    return
  }

  const res = await user.register({
    name: form.value.name,
    email: form.value.email,
    password: form.value.password,
    password_confirmation: form.value.password_confirmation,
    legal_acceptances: [
      {
        document_key: 'terms',
        document_version: legalDocuments.terms.version,
        document_hash: legalDocuments.terms.hash,
        action: 'accepted',
      },
      {
        document_key: 'privacy',
        document_version: legalDocuments.privacy.version,
        document_hash: legalDocuments.privacy.hash,
        action: 'acknowledged',
      },
    ],
  })

  if (!res.ok) {
    setErrorsFromResponse(res.error)
    return
  }

  redirectAuthedHome()
}
</script>

<template>
  <div class="auth-page container container-small grid gap-12">
    <h1>Registrieren</h1>

    <form class="auth-form grid gap-12" @submit.prevent="onSubmit">
      <FormGroup name="name" label="Name" :error="getError('name')" required>
        <FormFieldText
            v-model.trim="form.name"
            name="name"
            autocomplete="name"
            placeholder="Vorname Nachname"
            :invalid="hasError('name')"
            @update:modelValue="clearError('name')"
        />
      </FormGroup>

      <FormGroup name="email" label="E-Mail" :error="getError('email')" required>
        <FormFieldText
            v-model.trim="form.email"
            name="email"
            type="email"
            autocomplete="email"
            inputmode="email"
            :invalid="hasError('email')"
            @update:modelValue="clearError('email')"
        />
      </FormGroup>

      <FormGroup name="password" label="Passwort" :error="getError('password')" required>
        <FormFieldText
            v-model="form.password"
            name="password"
            type="password"
            autocomplete="new-password"
            :invalid="hasError('password')"
            @update:modelValue="clearError('password')"
        />
      </FormGroup>

      <FormGroup name="password_confirmation" label="Passwort wiederholen" :error="getError('password_confirmation')" required>
        <FormFieldText
            v-model="form.password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            :invalid="hasError('password_confirmation')"
            @update:modelValue="clearError('password_confirmation')"
        />
      </FormGroup>

      <div>
        <CheckBox v-model="form.terms_accepted" @update:modelValue="clearError('terms_accepted')">
          Ich akzeptiere die <RouterLink to="/legal/terms" target="_blank">Nutzungsbedingungen</RouterLink>.
        </CheckBox>
        <p v-if="getError('terms_accepted')" class="text-danger">{{ getError('terms_accepted') }}</p>
      </div>

      <div>
        <CheckBox v-model="form.privacy_acknowledged" @update:modelValue="clearError('privacy_acknowledged')">
          Ich habe die <RouterLink to="/legal/privacy" target="_blank">Datenschutzerklärung</RouterLink> zur Kenntnis genommen.
        </CheckBox>
        <p v-if="getError('privacy_acknowledged')" class="text-danger">{{ getError('privacy_acknowledged') }}</p>
      </div>

      <button class="btn btn-primary" type="submit" :disabled="user.isLoading">
        {{ user.isLoading ? 'Konto wird erstellt...' : 'Konto erstellen' }}
      </button>

      <p v-if="getError('_general')" class="text-danger">{{ getError('_general') }}</p>
    </form>

    <p class="hint">
      Sie haben bereits ein Konto?
      <RouterLink to="/login">Anmelden</RouterLink>
    </p>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" ;
.auth-page {
  margin: 40px auto;

}
.hint { margin-top: 12px; }
</style>
