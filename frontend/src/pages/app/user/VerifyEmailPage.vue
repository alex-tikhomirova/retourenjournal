<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { auth } from '@/api/auth'
import { useUserStore } from '@/stores/user'
import { useOrgStore } from '@/stores/org'

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const orgStore = useOrgStore()

const error = ref('')

onMounted(async () => {
  try {
    const { id, hash, expires, signature } = route.query

    if (!id || !hash || !expires || !signature) {
      error.value = 'Invalid verification link'
      return
    }

    const urlPath =
        `/api/auth/verify-email/${id}/${hash}?expires=${encodeURIComponent(expires)}&signature=${encodeURIComponent(signature)}`

    // verify endpoint может вернуть user (как мы обсуждали)
    await auth.verifyEmail(urlPath)

    // обновим user/org состояния
    await userStore.fetchUser({ force: true })
    orgStore.reset()
    if (userStore.isLoggedIn) {
      await orgStore.fetchOrganization({ force: true })
    }

    // редирект по твоим правилам
    if (!userStore.isVerified) {
      return router.replace('/app/email-not-verified')
    }
    if (orgStore.organization === false) {
      return router.replace('/app/welcome')
    }
    return router.replace('/app/returns')
  } catch (e) {
    error.value = 'Verification failed'
  }
})
</script>

<template>
  <div style="padding: 24px">
    <h1>Verifying email…</h1>
    <p v-if="error">{{ error }}</p>
  </div>
</template>
