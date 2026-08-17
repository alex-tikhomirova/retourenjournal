<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { auth } from '@/api/auth'
import { useUserStore } from '@/stores/user'
import { useOrgStore } from '@/stores/org'
import PageCard from "@/components/PageCard.vue";

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const orgStore = useOrgStore()

const error = ref('')

onMounted(async () => {
  try {
    const { id, hash, expires, signature } = route.query

    if (!id || !hash || !expires || !signature) {
      error.value = 'Der Bestätigungslink ist ungültig oder unvollständig'
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
    error.value = 'Die E-Mail-Adresse konnte nicht bestätigt werden. Bitte fordern Sie einen neuen Bestätigungslink an'
  }
})
</script>

<template>
  <div class="page-verify-email container container-small">
    <PageCard class="padded" title="E-Mail-Bestätigung">
      <div class="grid gap-24">
        <h3>E-Mail-Adresse wird bestätigt…</h3>
        <p v-if="error">{{ error }}</p>
      </div>
    </PageCard>
  </div>
</template>
<style scoped lang="scss">

</style>