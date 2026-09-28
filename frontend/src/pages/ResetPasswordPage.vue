<script setup>
import {computed, reactive, ref} from 'vue'
import {useRoute} from 'vue-router'
import {auth} from '@/api/auth.js'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import {useFormErrors} from '@/utils/useFormErrors.js'

const route = useRoute()
const isLoading = ref(false)
const isReset = ref(false)

const form = reactive({
  email: typeof route.query.email === 'string' ? route.query.email : '',
  token: typeof route.query.token === 'string' ? route.query.token : '',
  password: '',
  password_confirmation: '',
})

const hasValidLink = computed(() => Boolean(form.email && form.token))

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

  if (!hasValidLink.value) {
    setErrors({_general: ['Der Link zum Zurücksetzen des Passworts ist ungültig.']})
    return
  }

  const requiredErrors = {}
  for (const field of ['password', 'password_confirmation']) {
    if (!form[field]) requiredErrors[field] = ['Dieses Feld ist erforderlich.']
  }
  if (Object.keys(requiredErrors).length) {
    setErrors(requiredErrors)
    return
  }

  if (form.password !== form.password_confirmation) {
    setErrors({password_confirmation: ['Die Passwörter stimmen nicht überein.']})
    return
  }

  isLoading.value = true

  try {
    await auth.resetPassword({...form})
    isReset.value = true
  } catch (error) {
    setErrorsFromResponse(error)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="auth-page container container-small grid gap-12">
    <h1>Neues Passwort</h1>

    <template v-if="isReset">
      <p>Ihr Passwort wurde erfolgreich geändert.</p>
      <p class="hint"><RouterLink to="/login">Jetzt anmelden</RouterLink></p>
    </template>

    <form v-else class="auth-form grid gap-12" @submit.prevent="onSubmit">
      <FormGroup name="email" label="E-Mail">
        <FormFieldText
            v-model="form.email"
            name="email"
            type="email"
            autocomplete="email"
            disabled
        />
      </FormGroup>

      <FormGroup name="password" label="Neues Passwort" :error="getError('password')" required>
        <FormFieldText
            v-model="form.password"
            name="password"
            type="password"
            autocomplete="new-password"
            :invalid="hasError('password')"
            @update:modelValue="clearError('password')"
        />
      </FormGroup>

      <FormGroup
          name="password_confirmation"
          label="Passwort wiederholen"
          :error="getError('password_confirmation')"
          required
      >
        <FormFieldText
            v-model="form.password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            :invalid="hasError('password_confirmation')"
            @update:modelValue="clearError('password_confirmation')"
        />
      </FormGroup>

      <button class="btn btn-primary" type="submit" :disabled="isLoading || !hasValidLink">
        {{ isLoading ? 'Passwort wird gespeichert...' : 'Passwort speichern' }}
      </button>

      <p v-if="!hasValidLink" class="text-danger">
        Der Link zum Zurücksetzen des Passworts ist ungültig.
      </p>
      <p v-else-if="getError('_general')" class="text-danger">{{ getError('_general') }}</p>
    </form>
  </div>
</template>

<style scoped lang="scss">
.auth-page {
  margin: 40px auto;
}

.hint {
  margin-top: 12px;
}
</style>
