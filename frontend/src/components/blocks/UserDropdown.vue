<script setup>
import {computed, onBeforeUnmount, onMounted, ref} from 'vue'
import {Building2, ChevronDown, LogOut, UserRound} from 'lucide-vue-next'
import {useRouter} from 'vue-router'
import {useUserStore} from '@/stores/user.js'
import {useOrgStore} from '@/stores/org.js'
import UserAvatar from '@/components/ui/UserAvatar.vue'

const userStore = useUserStore()
const orgStore = useOrgStore()
const router = useRouter()
const root = ref(null)
const isOpen = ref(false)

const user = computed(() => userStore.user || {})
const organizationName = computed(() => orgStore.organization?.name || 'Keine Organisation')

const close = () => {
  isOpen.value = false
}

const onDocumentClick = (event) => {
  if (!root.value?.contains(event.target)) close()
}

const onKeydown = (event) => {
  if (event.key === 'Escape') close()
}

const logout = async () => {
  close()
  await userStore.logout()
  await router.push('/login')
}

onMounted(() => {
  document.addEventListener('click', onDocumentClick)
  document.addEventListener('keydown', onKeydown)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', onDocumentClick)
  document.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <div ref="root" class="user-dropdown">
    <button class="user-dropdown-trigger" type="button" :aria-expanded="isOpen" @click="isOpen = !isOpen">
      <span class="user-dropdown-summary">
        <span class="user-dropdown-name">{{ user.name }}</span>
        <span class="user-dropdown-organization">{{ organizationName }}</span>
      </span>
      <UserAvatar :name="user.name"/>
      <ChevronDown class="user-dropdown-chevron" :class="{'is-open': isOpen}"/>
    </button>

    <div v-if="isOpen" class="user-dropdown-menu">
      <div class="user-dropdown-details">
        <div class="font-500">{{ user.name }}</div>
        <div class="text-small text-muted user-dropdown-email">{{ user.email }}</div>
        <div class="text-small user-dropdown-org-full">{{ organizationName }}</div>
      </div>
      <nav class="user-dropdown-links" @click="close">
        <RouterLink to="/app/profile"><UserRound/>Profil</RouterLink>
        <RouterLink to="/app/organization"><Building2/>Organisation</RouterLink>
        <button type="button" :disabled="userStore.isLoading" @click="logout"><LogOut/>Abmelden</button>
      </nav>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.user-dropdown { position: relative; margin-left: auto; }
.user-dropdown-trigger {
  display: flex; align-items: center; gap: 10px; max-width: 290px; padding: 0;
  border: 0; background: transparent; color: inherit; cursor: pointer; text-align: right;
}
.user-dropdown-summary { min-width: 0; display: grid; gap: 2px; }
.user-dropdown-name, .user-dropdown-organization { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.user-dropdown-name { font-size: 14px; font-weight: 500; }
.user-dropdown-organization { font-size: 12px; color: variables.$text-color-muted; }
.user-dropdown-chevron { width: 16px; transition: transform 140ms ease; }
.user-dropdown-chevron.is-open { transform: rotate(180deg); }
.user-dropdown-menu {
  position: absolute; z-index: 30; top: calc(100% + 10px); right: 0; width: 280px;
  border: 1px solid variables.$border-color; border-radius: variables.$border-radius;
  background: variables.$background-color; box-shadow: 0 10px 30px rgba(16, 24, 40, .12);
}
.user-dropdown-details { display: grid; gap: 4px; padding: 16px; border-bottom: 1px solid variables.$border-color; }
.user-dropdown-email, .user-dropdown-org-full { overflow-wrap: anywhere; }
.user-dropdown-links { padding: 6px; }
.user-dropdown-links a, .user-dropdown-links button {
  width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px;
  border: 0; border-radius: variables.$border-radius; background: transparent;
  color: variables.$text-color; font: inherit; font-size: 14px; text-decoration: none; cursor: pointer;
}
.user-dropdown-links a:hover, .user-dropdown-links button:hover { background: variables.$head-bg-color; }
.user-dropdown-links svg { width: 17px; height: 17px; color: variables.$text-color-muted; }
</style>
