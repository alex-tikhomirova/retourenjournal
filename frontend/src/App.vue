<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'
import GuestLayout from '@/layouts/GuestLayout.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import "@/assets/scss/main.scss";

const route = useRoute()
const user = useUserStore()

const layoutComponent = computed(() => {
  // если явно указано — используем
  if (route.meta?.layout === 'app') {
    return AppLayout
  }
  if (route.meta?.layout === 'guest') {
    return GuestLayout
  }

  // fallback: по пути
  if (route.path.startsWith('/app')) {
    return AppLayout
  }
  return GuestLayout
})
</script>

<template>
  <component  :is="layoutComponent">
    <router-view />
  </component>
</template>