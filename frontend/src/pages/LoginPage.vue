<script setup>
import {reactive, ref} from 'vue'
import {useRouter} from 'vue-router'
import {useUserStore} from '@/stores/user.js'
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormGroup from "@/components/forms/FormGroup.vue";

const router = useRouter()
const user = useUserStore()

const form = reactive({
  email: '',
  password: '',
})

const error = ref('')

function redirectAuthedHome() {
  if (!user.isVerified) {
    return router.push('/app/email-not-verified')
  }
  if (!user.user?.current_organization_id) {
    return router.push('/app/welcome')
  }
  return router.push('/app/returns')
}

async function onSubmit() {
  error.value = ''

  if (!form.email || !form.password) {
    error.value = 'Bitte geben Sie E-Mail und Passwort ein.'
    return
  }

  const res = await user.login(form.email, form.password)

  if (!res.ok) {
    error.value = 'Anmeldung fehlgeschlagen.'
    return
  }

  redirectAuthedHome()
}
</script>

<template>
  <div class="auth-page">

    <h1>Anmelden</h1>
    <br/>

    <form class="auth-form" @submit.prevent="onSubmit">
      <FormGroup label="Email" name="email">
        <FormFieldText
            v-model.trim="form.email"
            type="email"
            name="email"
            autocomplete="email"
            inputmode="email"
            required
            />
      </FormGroup>
      <FormGroup label="Passwort" name="email">
        <FormFieldText
            v-model="form.password"
            name="password"
            type="password"
            autocomplete="current-password"
            required
        />
      </FormGroup>
      <button class="btn btn-primary" type="submit" :disabled="user.isLoading">
        {{ user.isLoading ? 'Anmeldung läuft…' : 'Anmelden' }}
      </button>

      <p v-if="error" class="text-danger">{{ error }}</p>
    </form>

    <p class="hint">
      Noch kein Konto?
      <RouterLink to="/register">Konto erstellen</RouterLink>
    </p>
  </div>
</template>

<style scoped>
.auth-page {
  max-width: 420px;
  margin: 40px auto;
  padding: 16px;
}

.auth-form {
  display: grid;
  gap: 16px;
}

</style>
