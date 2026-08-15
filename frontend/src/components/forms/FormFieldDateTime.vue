<script setup>
import {computed} from 'vue'
import {VueDatePicker} from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
  name: String,
  placeholder: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  invalid: {
    type: Boolean,
    default: false,
  },
  inputClass: {
    type: [String, Array],
    default: '',
  },
})

const model = defineModel({
  type: [String, null],
})

const pickerValue = computed({
  get: () => {
    if (!model.value) return null

    const date = new Date(model.value)
    return Number.isNaN(date.getTime()) ? null : date
  },
  set: (date) => {
    model.value = date instanceof Date ? date.toISOString() : null
  },
})

const inputId = computed(() => `field-${props.name}`)
const inputAttrs = computed(() => ({
  id: inputId.value,
  name: props.name,
  state: props.invalid ? false : undefined,
}))
const ui = computed(() => ({
  input: [
    `input-${props.name}`,
    ...(Array.isArray(props.inputClass) ? props.inputClass : [props.inputClass]),
    props.invalid ? 'is-invalid' : '',
  ].filter(Boolean),
}))
const inputSizeClass = computed(() => {
  const classes = (Array.isArray(props.inputClass) ? props.inputClass : [props.inputClass])
      .flatMap(value => value.split(' '))

  if (classes.includes('input-xs')) return 'form-field-date-time--xs'
  if (classes.includes('input-sm')) return 'form-field-date-time--sm'
  return ''
})

const formats = {
  input: 'dd.MM.yyyy HH:mm',
}

const timeConfig = {
  is24: true,
  enableSeconds: false,
}
</script>

<template>
  <VueDatePicker
      v-model="pickerValue"
      auto-apply
      text-input
      :disabled="disabled"
      :placeholder="placeholder"
      :formats="formats"
      :time-config="timeConfig"
      :input-attrs="inputAttrs"
      :ui="ui"
      :aria-invalid="invalid"
      :aria-describedby="`${inputId}-error`"
      :class="inputSizeClass"
  />
</template>

<style scoped>
.form-field-date-time--xs {
  --dp-font-size: 12px;
}

.form-field-date-time--sm {
  --dp-font-size: 13px;
}
</style>
