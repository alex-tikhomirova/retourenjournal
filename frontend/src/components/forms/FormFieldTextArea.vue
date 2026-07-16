<script setup>
import {computed} from 'vue'

const props = defineProps({
  name: String,
  rows: {
    type: [Number, String],
    default: 2
  },
  invalid: {
    type: Boolean,
    default: false,
  }
})
const value = defineModel({
  type: [String, Number, Boolean]
})

const inputId = computed(() => `field-${props.name}`)


</script>

<template>
  <textarea
      :name="name"
      :id="inputId"
      :value="value"
      :rows="rows"
      @input="$emit('update:modelValue', $event.target.value)"
      class="textarea"
      :class="[`input-${name}`, { 'is-invalid': invalid }]"
      :aria-invalid="invalid"
      :aria-describedby="`${inputId}-error`"
  />
</template>
