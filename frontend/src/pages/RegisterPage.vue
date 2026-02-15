<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user.js'

const router = useRouter()
const user = useUserStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const error = ref('')

function redirectAuthedHome() {
  if (!user.isVerified) return router.push('/app/email-not-verified')
  if (!user.user?.current_organization_id) return router.push('/app/welcome')
  return router.push('/app/returns')
}

async function onSubmit() {
  error.value = ''

  if (!form.name || !form.email || !form.password || !form.password_confirmation) {
    error.value = 'Please fill in all fields.'
    return
  }

  if (form.password !== form.password_confirmation) {
    error.value = 'Passwords do not match.'
    return
  }

  const res = await user.register({
    name: form.name,
    email: form.email,
    password: form.password,
    password_confirmation: form.password_confirmation,
  })

  if (!res.ok) {
    error.value = 'Registration failed.'
    return
  }

  redirectAuthedHome()
}
</script>

<template>
  <div class="auth-page">
    <h1>Register</h1>

    <form class="auth-form" @submit.prevent="onSubmit">
      <label class="field">
        <span>Name</span>
        <input v-model.trim="form.name" type="text" autocomplete="name" required />
      </label>

      <label class="field">
        <span>Email</span>
        <input
            v-model.trim="form.email"
            type="email"
            autocomplete="email"
            inputmode="email"
            required
        />
      </label>

      <label class="field">
        <span>Password</span>
        <input v-model="form.password" type="password" autocomplete="new-password" required />
      </label>

      <label class="field">
        <span>Repeat password</span>
        <input
            v-model="form.password_confirmation"
            type="password"
            autocomplete="new-password"
            required
        />
      </label>

      <button class="btn" type="submit" :disabled="user.isLoading">
        {{ user.isLoading ? 'Creating…' : 'Create account' }}
      </button>

      <p v-if="error" class="error">{{ error }}</p>
    </form>

    <p class="hint">
      Already have an account?
      <RouterLink to="/login">Login</RouterLink>
    </p>
  </div>
</template>

<style scoped>
.auth-page { max-width: 420px; margin: 40px auto; padding: 16px; }
.auth-form { display: grid; gap: 12px; }
.field { display: grid; gap: 6px; }
.field span { font-size: 14px; opacity: .8; }
input { padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; }
.btn { padding: 10px 12px; border-radius: 8px; border: 0; cursor: pointer; }
.btn:disabled { opacity: .6; cursor: not-allowed; }
.error { color: #b00020; margin: 0; }
.hint { margin-top: 12px; }
</style>
