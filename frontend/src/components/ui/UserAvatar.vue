<script setup>
import {computed} from 'vue'

const props = defineProps({
  name: {type: String, default: ''},
})

const initials = computed(() => {
  const parts = props.name.trim().split(/\s+/).filter(Boolean)
  if (!parts.length) return '?'

  return [parts[0], parts.length > 1 ? parts.at(-1) : null]
      .filter(Boolean)
      .map(part => Array.from(part)[0])
      .join('')
      .toLocaleUpperCase()
})
</script>

<template>
  <span class="user-avatar" aria-hidden="true">{{ initials }}</span>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.user-avatar {
  width: 40px;
  height: 40px;
  flex: 0 0 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: variables.$color-primary;
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: .02em;
}
</style>
