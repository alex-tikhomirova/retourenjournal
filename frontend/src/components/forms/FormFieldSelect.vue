<script setup>
import {computed} from 'vue'

const props = defineProps({
  name: String,
  modelValue: [String, Number, Boolean],
  options: {
    type: Array,
    default: () => []
  },
  placeholder: {
    type: String,
    default: "",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  invalid: {
    type: Boolean,
    default: false,
  }
})

const model = defineModel({
  type: [String, Number, Boolean, null],
});

const inputId = computed(() => `field-${props.name}`)

const normalizedOptions = computed(() => props.options.map((option) => {
  if (option !== null && typeof option === 'object') {
    return option
  }
  return {
    label: option,
    value: option,
  }
}))

</script>

<template>
  <select
      :id="inputId"
      v-model="model"
      :name="name"
      :disabled="disabled"
      class="input"
      :class="[`input-${name}`, { 'is-invalid': invalid }]"
      :aria-invalid="invalid"
      :aria-describedby="`${inputId}-error`"
  >
    <option v-if="placeholder" disabled value="">
      {{ placeholder }}
    </option>

    <option
        v-for="option in normalizedOptions"
        :key="option.value"
        :value="option.value"
    >
      {{ option.label }}
    </option>
  </select>

</template>
