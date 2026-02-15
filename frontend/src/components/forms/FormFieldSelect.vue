<script setup>
import {computed, ref, watch} from 'vue'

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
})

const model = defineModel({
  type: [String, Number, Boolean, null],
});

const inputId = computed(() => `field-${props.name}`)

</script>

<template>
  <select
      :id="inputId"
      v-model="model"
      :name="name"
      :disabled="disabled"
      class="input"
      :class="`input-${name}`"
      :aria-invalid="false"
      :aria-describedby="`${inputId}-error`"
  >
    <option v-if="placeholder" disabled value="">
      {{ placeholder }}
    </option>

    <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
    >
      {{ option.label }}
    </option>
  </select>

</template>
