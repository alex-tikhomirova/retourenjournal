<script setup>
import { computed } from 'vue'

import FormFieldText from '@/components/forms/FormFieldText.vue'
import DateRangeFilter from '@/components/forms/filter/DateRangeFilter.vue'
import StatusFilter from '@/components/forms/filter/StatusFilter.vue'
import { debounce } from '@/utils/debounce.js'
import { RotateCcw } from 'lucide-vue-next'

const props = defineProps({
  loading: {
    type: Boolean,
    default: false,
  }
})

const filter = defineModel({
  type: Object,
  required: true,
})

defineEmits(['reset'])
const updateFilter = (key, value) => {
  filter.value = {
    ...filter.value,
    [key]: value,
  }
}

const updateSearch = debounce((value) => {
  updateFilter('q', value)
}, 700)

const search = computed({
  get: () => filter.value.q,
  set: (value) => updateSearch(value),
})

const statusId = computed({
  get: () => filter.value.status_id,
  set: (value) => updateFilter('status_id', value),
})

const createdAt = computed({
  get: () => filter.value.created_at,
  set: (value) => updateFilter('created_at', value),
})
</script>

<template>
  <div class="returns-filters flex gap-36">
      <div class="filter-text flex-2">
        <FormFieldText
          v-model="search"
          name="returns-search"
          placeholder="Retourennummer, Bestellung, Kunde, SKU oder Artikel suchen"
        />
      </div>
      <StatusFilter class="flex-1"
        v-model="statusId"
      />
      <DateRangeFilter class="flex-1"
        v-model="createdAt"
      />
      <button
        type="button"
        class="btn btn-outline-primary"
        :disabled="props.loading"
        @click="$emit('reset')"
      ><RotateCcw /> Zurücksetzen
      </button>

  </div>
</template>

<style scoped lang="scss">


</style>
