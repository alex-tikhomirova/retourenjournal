<script setup>
  import {api} from "@/api/api.js";
  import {ref} from "vue";
  import ToolBar from "@/components/ToolBar.vue";
  import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
  import {dateTimeStr} from "@/helpers/datetime.js";
  const returns = ref([])
  api.post('/api/returns/list').then((resp) => {
    returns.value = resp.data.data
  })
</script>

<template>
  <ToolBar title="Retouren" subtitle="Verwalten, prüfen und abschließen Sie Ihre Retouren.">
    <template #right>
      <RouterLink class="btn btn-primary" to="/app/returns/new">Neue Rückgabe</RouterLink>
    </template>
  </ToolBar>
  <table v-if="returns.length" class="table grid-table">
    <thead>
    <tr>
      <th>Retourennummer</th>
      <th>Bestellnummer</th>
      <th>Kunde</th>
      <th>Status</th>
      <th>Erstellt am</th>
      <th>Aktualisiert  am</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in returns">
      <td>
        <router-link :to="`/app/returns/${item.id}`">
        {{item.return_number}}
        </router-link>
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
  <div v-else>
    Die Rückgabeliste ist leer
  </div>




</template>

<style scoped>

</style>