<script setup>
  import {api} from "@/api/api.js";
  import {ref} from "vue";
  import ToolBar from "@/components/ToolBar.vue";
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
  <table v-if="returns.length" class="grid-table">
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
        <div v-if="item.status">
          {{item.status.name}}
        </div>
      </td>
      <td>{{item.created_at}}</td>
      <td>{{item.updated_at}}</td>
    </tr>
    </tbody>
  </table>
  <div v-else>
    Die Rückgabeliste ist leer
  </div>




</template>

<style scoped>

</style>