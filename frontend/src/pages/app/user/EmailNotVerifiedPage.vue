<script setup>
import { ref } from 'vue'
import { auth } from '@/api/auth'
import { useUserStore } from '@/stores/user'
import PageCard from "@/components/PageCard.vue";

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
  <div class="page-not-verified container container-small">
    <PageCard class="padded" title="E-Mail-Bestätigung">
      <div class="grid gap-24">
        <h3>Bitte bestätigen Sie Ihre E-Mail-Adresse</h3>
        <p class="text-muted">Öffnen Sie Ihr Postfach und klicken Sie auf den Bestätigungslink, um fortzufahren.</p>
        <button class="btn btn-outline-primary resend" @click="resend">
          Bestätigungs-E-Mail erneut senden
        </button>
        <div class="messages" v-if="status || error">
          <span v-if="status" class="text-success">{{ status }}</span>
          <span v-if="error" class="text-danger">{{ error }}</span>
        </div>
        <p class="status" v-if="userStore.user && userStore.user.email">
          Angemeldet als: {{ userStore.user.email }}
        </p>
      </div>
    </PageCard>




  </div>
</template>
