<script setup>
import ToolBar from "@/components/ToolBar.vue";
import {useRoute, useRouter} from "vue-router";
import {computed, ref, watch} from "vue";
import {api} from "@/api/api.js";
import PageCard from "@/components/PageCard.vue";
import ReturnEvent from "@/pages/app/return/ReturnEvent.vue";

import {Check, X, Pencil} from "lucide-vue-next";
import ReturnShipmentForm from "@/pages/app/return/ReturnShipmentForm.vue";

const router = useRouter()
const route = useRoute()

const returnId = computed(() => route.params.id);

const returnData = ref()
const state = ref(0)
const shippingForm = ref(false)
async function load() {
  state.value = -1
  shippingForm.value = false
  try {
    const { data } = await api.get(`/api/returns/${returnId.value}`);
    returnData.value = data.data
  } catch (e) {
    //state.error = e;
    // опционально: редирект на 404
    // router.replace("/app/returns");
  } finally {
    state.value = 0
  }
}

watch(returnId, () => {
  if (!returnId.value) return
  load()
}, { immediate: true })

const subtitle = computed(() => {
  return `Status: ${returnData.value.status.name} · Zuletzt aktualisiert: ${returnData.value.updated_at}`
})


const save = () => {}
</script>

<template>

  <ToolBar v-if="returnData" :on-back="() => router.push('/app/returns')" :title="`Retoure ${returnData.return_number}`" :subtitle="subtitle">
    <template #right>
      <button class="btn btn-outline-primary" @click="router.push('/app/returns')"><X /> Abbrechen</button>
      <button class="btn btn-primary" @click="save"><Check/> Speichern</button>
    </template>
  </ToolBar>

  <div v-if="returnData" class="flex gap-30 return-data items-start">
    <div class="left">

      <PageCard title="Kunde" class="block-customer">
        <template #title>
          <button class="btn btn-sm btn-outline-secondary"><Pencil />Bearbeiten</button>
        </template>
        <div>{{ returnData.customer.name }}</div>
        <div>{{ returnData.customer.phone }}</div>
        <div>{{ returnData.customer.email }}</div>
      </PageCard>
      <PageCard title="Artikel" class="block-items">
        <table class="table grid-table">
          <thead>
          <tr>
            <th class="pos">Pos.</th>
            <th class="sku">SKU</th>
            <th class="name">Artikel</th>
            <th class="qty">Menge</th>
            <th class="price">Preis</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="item in returnData.items">
            <td>{{item.line_no}}</td>
            <td>{{item.sku}}</td>
            <td>{{item.item_name}}</td>
            <td>{{item.quantity}}</td>
            <td>{{item.unit_price_cents}}</td>
          </tr>
          </tbody>
        </table>

      </PageCard>
      <PageCard title="Versand & Logistik" class="block-shipping">
        <ReturnShipmentForm
            :return_id="returnData.id"
            v-if="shippingForm"
            @close="shippingForm = false"
            @saved="load"
        />
        <div>

          <div v-for="item in returnData.shipments">
            {{item.id}}
            {{item.direction}}
            {{item.carrier}}
            {{item.payer}}
            {{item.cost_cents}}
            {{item.status.name}}
            {{item.created_by.name}}
            {{item.updated_at}}
          </div>
        </div>
        <button class="btn btn-primary" v-if="!shippingForm" @click="shippingForm = true">Versand anlegen</button>
      </PageCard>
      <PageCard title="Erstattungen" class="block-refund"></PageCard>

    </div>
    <PageCard title="Akivitäten" class="history">
      <ReturnEvent v-for="event in returnData.events" :event="event"/>
    </PageCard>
  </div>
</template>

<style lang="scss">
  .return-data{
    .left{
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .history{
      width: 20%;
      min-width: 300px;
    }
  }
</style>