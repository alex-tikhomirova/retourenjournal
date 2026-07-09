<script setup>
import { computed } from 'vue'
import { VueDatePicker as Datepicker } from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const model = defineModel({
  type: Array,
  default: () => [],
})

const datepickerFormats = {
  input: 'dd.MM.yy',
}

const timeConfig = {
  enableTimePicker: false,
}

const pickerValue = computed({
  get: () => {
    const from = model.value?.[0] ? new Date(model.value[0]) : null
    const to = model.value?.[1] ? new Date(model.value[1]) : null

    return from || to ? [from, to] : null
  },
  set: (range) => {
    model.value = [
      toDateString(range?.[0]),
      toDateString(range?.[1]),
    ].filter(Boolean)
  },
})

const activePreset = computed(() => {
  if (sameRange(model.value, lastDaysRange(1))) {
    return 'today'
  }

  if (sameRange(model.value, lastDaysRange(7))) {
    return '7-days'
  }

  if (sameRange(model.value, lastDaysRange(30))) {
    return '30-days'
  }

  return null
})

const lastDaysRange = (days) => {
  const end = dateOnly(new Date())
  const start = dateOnly(new Date())
  start.setDate(start.getDate() - days + 1)
  return [start, end]
}

const thisMonthRange = () => {
  const now = new Date()
  return [
    dateOnly(new Date(now.getFullYear(), now.getMonth(), 1)),
    dateOnly(now),
  ]
}

const dateOnly = (date) => {
  const result = new Date(date)
  result.setHours(12, 0, 0, 0)
  return result
}

const setRange = (range) => {
  pickerValue.value = range
}

const reset = () => {
  model.value = []
}

const sameRange = (value, range) => {
  return value?.[0] === toDateString(range[0])
    && value?.[1] === toDateString(range[1])
}

const toDateString = (date) => {
  if (!date) {
    return null
  }

  const result = new Date(date)
  const year = result.getFullYear()
  const month = String(result.getMonth() + 1).padStart(2, '0')
  const day = String(result.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}
</script>

<template>
  <div class="date-range-filter">
    <Datepicker
      v-model="pickerValue"
      range
      auto-apply
      :formats="datepickerFormats"
      :time-config="timeConfig"
      placeholder="Zeitraum auswählen"
    />

    <div class="date-range-filter__presets">
      <button
        type="button"
        class="btn"
        :class="activePreset === 'today' ? 'btn-primary' : 'btn-outline-primary'"
        @click="setRange(lastDaysRange(1))"
      >
        Heute
      </button>
      <button
        type="button"
        class="btn"
        :class="activePreset === '7-days' ? 'btn-primary' : 'btn-outline-primary'"
        @click="setRange(lastDaysRange(7))"
      >
        7 Tage
      </button>
      <button
        type="button"
        class="btn"
        :class="activePreset === '30-days' ? 'btn-primary' : 'btn-outline-primary'"
        @click="setRange(lastDaysRange(30))"
      >
        30 Tage
      </button>
    </div>
  </div>
</template>

<style scoped lang="scss">
.date-range-filter {
  display: flex;
  gap: 6px;
  .date-range-filter__presets {
    display: flex;
    gap: 6px;
  }
}


</style>
