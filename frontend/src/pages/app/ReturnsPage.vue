<script setup>
  import {api} from "@/api/api.js";
  import {computed, ref, watch} from "vue";
  import ToolBar from "@/components/ToolBar.vue";
  import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
  import {dateTimeStr} from "@/helpers/datetime.js";
  import ReturnsFilters from "@/components/returns/ReturnsFilters.vue";
  import {useStoredListState} from "@/helpers/useStoredListState.js";
  import PageCard from "@/components/PageCard.vue";
  import {useRouter} from "vue-router";
  const router = useRouter()
  import {Plus, RotateCcw} from "lucide-vue-next";
  import Pagination from "@/components/ui/Pagination.vue";
  import ColumnSortLink from "@/components/ui/ColumnSortLink.vue";

  const returns = ref([])

  const defaultFilter = {
    status_id: [],
    created_at: [],
    q: '',
  }

  const listState = useStoredListState('returns-list', {
    filter: defaultFilter,
    sort: '-created_at',
    pagination: {
      current_page: 1,
      per_page: 20,
      total: 0,
    },
  })
  const isDefaultFilter = computed(() => JSON.stringify(listState.filter) === JSON.stringify(defaultFilter))

  const loadingState = ref('')
  const errorResonse = ref(null)
  const loadReturns = async () => {
    loadingState.value = 'loading'
    try {
      const {data} = await api.post('/api/returns/list', listState.value)
      returns.value = data.data
      listState.value.pagination.total = data.meta?.total ?? 0
      loadingState.value = listState.value.pagination.total ? '' : (isDefaultFilter.value ? 'empty' : 'filtered-empty')
    }catch (e){
      loadingState.value = 'error'
      errorResonse.value = e.response
    }


  }
  watch(() => [
    listState.value.filter,
    listState.value.sort,
    listState.value.pagination.current_page,
    listState.value.pagination.per_page,
  ], () => {
    loadReturns()
  }, { deep: true, immediate: true })
</script>

<template>
  <div class="returns-page">
  <ToolBar title="Retouren" subtitle="Verwalten, prüfen und abschließen Sie Ihre Retouren.">

    <template #right>
      <RouterLink class="btn btn-primary" to="/app/returns/new"><Plus/> Neue Rückgabe</RouterLink>
    </template>
  </ToolBar>
  <PageCard class="filters">
  <ReturnsFilters v-model="listState.filter" :loading="loadingState === 'loading'" @reset="listState.filter = defaultFilter"/>
  </PageCard>
  <PageCard class="returns">
    <table class="table grid-table">
      <thead>
      <tr>
        <th><ColumnSortLink field="return_number" v-model="listState.sort">Retourennummer</ColumnSortLink></th>
        <th><ColumnSortLink field="order_reference" v-model="listState.sort">Bestellnummer</ColumnSortLink></th>
        <th><ColumnSortLink field="customer.name" v-model="listState.sort">Kunde</ColumnSortLink></th>
        <th><ColumnSortLink field="status_id" v-model="listState.sort">Status</ColumnSortLink></th>
        <th><ColumnSortLink field="created_at" v-model="listState.sort">Erstellt am</ColumnSortLink></th>
        <th><ColumnSortLink field="updated_at" v-model="listState.sort">Aktualisiert am</ColumnSortLink></th>
      </tr>
      </thead>
      <tbody>
      <tr v-if="loadingState" class="return-row">
        <td :colspan="6" class="table-state-cell">
          <div v-if="loadingState === 'empty'" class="loading-state">
            <div class="info">
              <h4>Noch keine Retouren vorhanden.</h4>
              <div class="text-muted">Legen Sie Ihre erste Retoure an, um den Prozess zu starten.</div>
            </div>
            <button class="btn btn-primary" @click="router.push('/app/returns/new')"><Plus/> Neue Rückgabe</button>
          </div>
          <div v-if="loadingState === 'filtered-empty'" class="loading-state">
            <div class="info">
              <h4>Keine Retouren gefunden.</h4>
              <div class="text-muted">Passen Sie die Filter an oder setzen Sie die Suche zurück.</div>
            </div>
            <button class="btn btn-outline-primary" @click="listState.filter = defaultFilter"><RotateCcw /> Filter zurücksetzen</button>
          </div>
          <div v-if="loadingState === 'loading'" class="loading-state">
            <div class="info">
              <div class="text-muted">Retouren werden geladen...</div>
            </div>
          </div>
          <div v-if="loadingState === 'error'" class="loading-state">
            <div class="info">
              <div class="text-danger">Retouren konnten nicht geladen werden. Bitte versuchen Sie es erneut.</div>
              <div class="text-muted text-small">Fehler: {{errorResonse?.data?.message ?? 'Unbekannter Fehler'}}</div>
            </div>
            <button class="btn btn-outline-danger" @click="loadReturns"><RotateCcw /> Erneut versuchen</button>
          </div>

        </td>
      </tr>
      <tr v-if="loadingState === ''" v-for="item in returns" class="return-row clickable" @click="router.push(`/app/returns/${item.id}`)">
        <td>
          {{item.return_number}}
        </td>
        <td>{{item.order_reference}}</td>
        <td>
          <div v-if="item.customer">
            {{item.customer.name}}
          </div>
        </td>
        <td>
          <ReturnStatusLabel v-if="item.status" :status="item.status"/>
        </td>
        <td>{{dateTimeStr(item.created_at)}}</td>
        <td>{{dateTimeStr(item.updated_at)}}</td>
      </tr>
      </tbody>
    </table>
  </PageCard>

    <Pagination v-model="listState.pagination" />

  </div>

</template>

<style scoped>
  .returns-page {

    .filters{
      margin-bottom: 18px;
      padding: 18px;
    }
    .returns{
      margin-bottom: 18px;

      .loading-state{
        padding: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        .info{
          display: grid;
          gap: 6px;
        }
      }
    }
  }
</style>
