<script setup>
import {computed, ref, watch} from 'vue'

const props = defineProps({
  name: String,
  type: {
    type: String,
    default: "text"
  }
})
const value = defineModel({
  type: [String, Number, Boolean]
})

const inputId = computed(() => `field-${props.name}`)


</script>

<template>
  <input
      :type="type"
      :name="name"
      :id="inputId"
      :value="value"
      @input="$emit('update:modelValue', $event.target.value)"
      class="input"
      :class="`input-${name}`"
      :aria-invalid="false"
      :aria-describedby="`${inputId}-error`"
  />
</template>
