<script setup>
import {useOrgStore} from "@/stores/org.js";

const org = useOrgStore()
</script>

<template>
<h1>Herzlich willkommen im RetourenJournal!</h1>

  <p>Um mit RetourenJournal zu starten, legen Sie bitte zunächst eine Organisation an.</p>
  <template v-if="!org.organization.id">
    <br/>
    <br/>
    <RouterLink class="btn btn-primary" to="/app/organization/new">Organisation erstellen</RouterLink>
  </template>

</template>

<style scoped>

</style>