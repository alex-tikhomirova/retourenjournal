<script setup>
import {ref} from 'vue'
import {auth} from '@/api/auth.js'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import {useFormErrors} from '@/utils/useFormErrors.js'

const email = ref('')
const isLoading = ref(false)
const isSent = ref(false)

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

  if (!email.value) {
    setErrors({email: ['Dieses Feld ist erforderlich.']})
    return
  }

  isLoading.value = true

  try {
    await auth.forgotPassword({email: email.value})
    isSent.value = true
  } catch (error) {
    setErrorsFromResponse(error)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="auth-page container container-small grid gap-12">
    <h1>Passwort zurücksetzen</h1>

    <template v-if="isSent">
      <p>
        Wenn ein Konto mit dieser E-Mail-Adresse existiert, erhalten Sie eine
        E-Mail mit einem Link zum Zurücksetzen des Passworts.
      </p>
      <p class="hint"><RouterLink to="/login">Zurück zur Anmeldung</RouterLink></p>
    </template>

    <form v-else class="auth-form grid gap-12" @submit.prevent="onSubmit">
      <FormGroup name="email" label="E-Mail" :error="getError('email')" required>
        <FormFieldText
            v-model.trim="email"
            name="email"
            type="email"
            autocomplete="email"
            inputmode="email"
            :invalid="hasError('email')"
            @update:modelValue="clearError('email')"
        />
      </FormGroup>

      <button class="btn btn-primary" type="submit" :disabled="isLoading">
        {{ isLoading ? 'E-Mail wird gesendet...' : 'Link anfordern' }}
      </button>

      <p v-if="getError('_general')" class="text-danger">{{ getError('_general') }}</p>
      <p class="hint"><RouterLink to="/login">Zurück zur Anmeldung</RouterLink></p>
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
