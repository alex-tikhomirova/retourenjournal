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
    status.value = 'Bestätigungs-E-Mail wurde versendet.'
  } catch (e) {
    error.value = 'Bestätigungs-E-Mail konnte nicht versendet werden.'
  }
}
</script>

<template>
  <div style="padding: 24px">
    <h1>Bitte bestätigen Sie Ihre E-Mail-Adresse</h1>
    <p class="text-muted">Überprüfen Sie Ihr Postfach und klicken Sie auf den Bestätigungslink.</p>

    <button class="btn btn-outline-primary resend" @click="resend">
      Bestätigungs-E-Mail erneut senden
    </button>

    <div class="messages">
      <span v-if="status" class="text-success">{{ status }}</span>
      <span v-if="error" class="text-danger">{{ error }}</span>
    </div>

    <p class="status" v-if="userStore.user && userStore.user.email">
      Angemeldet als: {{ userStore.user.email }}
    </p>
  </div>
</template>
<style scoped>
.resend{
  margin: 24px  0 4px 0;
}
.messages{
  margin-bottom: 24px;
}
</style>