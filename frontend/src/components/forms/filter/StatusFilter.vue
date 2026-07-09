<script setup>
import { computed } from 'vue'
import FormFieldSelect from '@/components/forms/FormFieldSelect.vue'
import { useLookupStore } from '@/stores/lookups.js'

const lookup = useLookupStore()

const model = defineModel({
  type: Array,
  default: () => [],
})

const statuses = computed(() => lookup.returnStatuses ?? [])

const openStatusIds = computed(() => statuses.value
  .filter((status) => Number(status.kind) !== 9)
  .map((status) => Number(status.id))
)

const terminalStatusIds = computed(() => statuses.value
  .filter((status) => Number(status.kind) === 9)
  .map((status) => Number(status.id))
)

const selectedStatusId = computed({
  get: () => model.value.length === 1 ? model.value[0] : '',
  set: (value) => {
    model.value = value ? [Number(value)] : []
  },
})

const statusOptions = computed(() => [
  { value: '', label: 'Alle Status' },
  ...statuses.value.map((status) => ({
    value: status.id,
    label: status.name,
  })),
])

const selectedStatus = computed(() => {
  if (!selectedStatusId.value) {
    return null
  }

  return statuses.value.find((status) => Number(status.id) === Number(selectedStatusId.value)) ?? null
})

const activePreset = computed(() => {
  if (sameIds(model.value, openStatusIds.value)) {
    return 'open'
  }

  if (sameIds(model.value, terminalStatusIds.value)) {
    return 'closed'
  }

  if (!model.value.length) {
    return 'all'
  }

  return 'custom'
})

function setPreset(preset) {
  if (preset === 'open') {
    model.value = [...openStatusIds.value]
    return
  }

  if (preset === 'closed') {
    model.value = [...terminalStatusIds.value]
    return
  }

  model.value = []
}

function sameIds(left, right) {
  if (left.length !== right.length) {
    return false
  }

  const a = left.map(Number).sort((x, y) => x - y)
  const b = right.map(Number).sort((x, y) => x - y)
  return a.every((id, index) => id === b[index])
}
</script>

<template>
  <div class="status-filter flex gap-8">
    <FormFieldSelect
        v-model="selectedStatusId"
        name="return-status-filter"
        :options="statusOptions"
    />
    <div class="status-filter__presets flex gap-8">
      <button
        type="button"
        class="btn "
        :class="activePreset === 'open' ? 'btn-primary' : 'btn-outline-primary'"
        @click="setPreset('open')"
      >
        Offen
      </button>
      <button
        type="button"
        class="btn "
        :class="activePreset === 'all' ? 'btn-primary' : 'btn-outline-primary'"
        @click="setPreset('all')"
      >
        Alle
      </button>
      <button
        type="button"
        class="btn "
        :class="activePreset === 'closed' ? 'btn-primary' : 'btn-outline-primary'"
        @click="setPreset('closed')"
      >
        Abgeschlossen
      </button>
    </div>
  </div>
</template>

<style scoped lang="scss">


</style>