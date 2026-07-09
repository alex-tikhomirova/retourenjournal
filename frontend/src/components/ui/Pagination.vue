<script setup>
import { computed } from 'vue'

const pagination = defineModel({
  type: Object,
  required: true,
})

const emit = defineEmits(['change'])

const currentPage = computed(() => Number(pagination.value?.current_page ?? 1))
const total = computed(() => Number(pagination.value?.total ?? 0))
const perPage = computed(() => Number(pagination.value?.per_page ?? 20))
const lastPage = computed(() => Math.max(Math.ceil(total.value / perPage.value), 1))
const from = computed(() => total.value ? ((currentPage.value - 1) * perPage.value) + 1 : 0)
const to = computed(() => Math.min(currentPage.value * perPage.value, total.value))

const pages = computed(() => {
  const start = Math.max(currentPage.value - 2, 1)
  const end = Math.min(start + 4, lastPage.value)
  const first = Math.max(end - 4, 1)

  return Array.from({ length: end - first + 1 }, (_, index) => first + index)
})

const updatePagination = (patch) => {
  const next = {
    ...pagination.value,
    ...patch,
  }

  pagination.value = next
  emit('change', next)
}

const goToPage = (page) => {
  const nextPage = Math.min(Math.max(Number(page), 1), lastPage.value)

  if (nextPage === currentPage.value) {
    return
  }

  updatePagination({
    current_page: nextPage,
  })
}

const updatePerPage = (event) => {
  updatePagination({
    current_page: 1,
    per_page: Number(event.target.value),
  })
}
</script>

<template>
  <nav v-if="total" class="pagination" aria-label="Pagination">
    <div class="pagination__summary">
      {{ from }}-{{ to }} von {{ total }}
    </div>

    <div class="pagination__pages">
      <button
        type="button"
        class="btn btn-sm btn-outline-primary"
        :disabled="currentPage <= 1"
        @click="goToPage(currentPage - 1)"
      >
        Zuruck
      </button>

      <button
        v-for="page in pages"
        :key="page"
        type="button"
        class="btn btn-sm"
        :class="page === currentPage ? 'btn-primary' : 'btn-outline-primary'"
        @click="goToPage(page)"
      >
        {{ page }}
      </button>

      <button
        type="button"
        class="btn btn-sm btn-outline-primary"
        :disabled="currentPage >= lastPage"
        @click="goToPage(currentPage + 1)"
      >
        Weiter
      </button>
    </div>

    <label class="pagination__per-page ws-nowrap">
      Pro Seite
      <select :value="perPage" class="input" @change="updatePerPage">
        <option :value="5">5</option>
        <option :value="20">20</option>
        <option :value="50">50</option>
        <option :value="100">100</option>
        <option :value="200">200</option>
      </select>
    </label>
  </nav>
</template>

<style scoped lang="scss">
.pagination {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
  justify-content: space-between;

}

.pagination__pages {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  align-items: center;
}

.pagination__summary,
.pagination__per-page {
  color: #667085;
  font-size: 14px;
}

.pagination__per-page {
  display: inline-flex;
  gap: 8px;
  align-items: center;
}

.pagination__per-page .input {
  min-width: 84px;
}
</style>
