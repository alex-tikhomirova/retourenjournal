<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: String,
  name: { type: String, required: true },
  error: String,
  required: { type: Boolean, default: false },
})

const inputId = computed(() => `field-${props.name}`)
</script>

<template>
  <div class="form-group" :class="`group-${name}`">
    <label>
      <span class="text-small font-500 label-text">{{ label }} <span v-if="required" class="text-danger">*</span></span>
      <slot/>
    </label>
    <p
        v-if="error"
        class="input-error"
        :id="`${inputId}-error`"
    >
      {{ error }}
    </p>
  </div>
</template>
<style scoped lang="scss">
.form-group{

  label{
    display: grid;
    gap: 6px;
  }

  &.horizontal {
    label{
      grid-template-columns: auto 1fr;
      gap: 12px;
      align-items: center;
    }
  }
}
</style>