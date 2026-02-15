<script setup>
import { ref } from 'vue'
import { auth } from '@/api/auth'
import { useUserStore } from '@/stores/user'

const userStore = useUserStore()
const status = ref('')
const error = ref('')

async function resend() {
  status.value = ''
  error.value = ''
  try {
    await auth.resendVerification()
    status.value = 'Verification email sent.'
  } catch (e) {
    error.value = 'Failed to send verification email.'
  }
}
</script>

<template>
  <div style="padding: 24px">
    <h1>Please verify your email</h1>
    <p>Check your inbox and click the verification link.</p>

    <button @click="resend">Resend verification email</button>

    <p v-if="status">{{ status }}</p>
    <p v-if="error">{{ error }}</p>

    <p v-if="userStore.user && userStore.user.email">
      Logged in as: {{ userStore.user.email }}
    </p>
  </div>
</template>
