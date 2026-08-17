<script setup>
import {useOrgStore} from "@/stores/org.js";
import {Plus, ArrowRight} from "lucide-vue-next"
import PageCard from "@/components/PageCard.vue";

const org = useOrgStore()
</script>

<template>
  <div class="welcome-page container container-small">

    <PageCard class="padded" title="Erste Schritte" v-if="!org.hasOrganization">
      <div class="grid gap-24" >
        <h3>Willkommen im RetourenJournal</h3>
        <p>Legen Sie eine Organisation an, um Retouren für Ihr Unternehmen zu verwalten.</p>

        <div class="button-block">
          <RouterLink class="btn btn-primary " to="/app/organization/new">
            <Plus/>
            Organisation erstellen
          </RouterLink>
        </div>
        <p class="text-muted">
          Die Organisation trennt Ihre Daten von anderen Unternehmen und ist die Grundlage für Retouren, Kunden,
          Sendungen und Erstattungen.
        </p>
      </div>
    </PageCard>
    <PageCard class="padded" title="Bereit zum Start" v-else>
      <div class="grid gap-24" >
        <h3>Alles bereit</h3>
        <p>
          Ihre Organisation ist eingerichtet. Sie können jetzt mit dem Retourenmanagement starten.

        </p>
        <div class="button-block">
          <RouterLink class="btn btn-primary " to="/app/returns">
            <ArrowRight />
            Zu den Retouren
          </RouterLink>
        </div>
      </div>
    </PageCard>
  </div>

</template>

<style scoped lang="scss">
  .welcome-page{
    .button-block{

      text-align: center;
    }
  }
</style>