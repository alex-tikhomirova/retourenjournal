<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: String,
  name: { type: String, required: true },
  error: String
})

const inputId = computed(() => `field-${props.name}`)
</script>

<template>
  <div class="form-group" :class="`group-${name}`">
    <label :for="inputId">{{ label }}</label>
    <slot/>
    <p
        v-if="error"
        class="error"
        :id="`${inputId}-error`"
    >
      {{ error }}
    </p>
  </div>
</template>
