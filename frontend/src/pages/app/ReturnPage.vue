<script setup>
import ToolBar from "@/components/ToolBar.vue";
import {useRoute, useRouter} from "vue-router";
import {computed, ref, watch} from "vue";
import {api} from "@/api/api.js";
import PageCard from "@/components/PageCard.vue";
import ReturnEvent from "@/pages/app/return/ReturnEvent.vue";

import {Pencil} from "lucide-vue-next";
import ReturnDecision from "@/pages/app/return/ReturnDecision.vue";
import StateSwitch from "@/pages/app/return/StateSwitch.vue";
import ItemsList from "@/pages/app/return/ItemsList.vue";
import CustomerInfo from "@/pages/app/return/CustomerInfo.vue";
import {dateTimeStr} from "@/helpers/datetime.js";
import ShipmentList from "@/pages/app/return/shipment/ShipmentList.vue";

const router = useRouter()
const route = useRoute()

const returnId = computed(() => route.params.id);

const returnData = ref(null)
const state = ref(0)
const shippingForm = ref(false)
async function load() {
  state.value = -1
  shippingForm.value = false
  try {
    const { data } = await api.get(`/api/returns/${returnId.value}`);
    returnData.value = data.data

  } catch (e) {
    // router.replace("/app/returns");
  } finally {
    state.value = 0
  }
}

const save = async () => {
  const {data} = await api.patch(`/api/returns/${returnId.value}`, returnData.value);
  returnData.value = data?.data ?? returnData.value
}


watch(returnId, () => {
  if (!returnId.value) return
  load()
}, { immediate: true })


const subtitle = computed(() => {
  return `Status: ${returnData.value.status.name} · Zuletzt aktualisiert: ${dateTimeStr(returnData.value.updated_at, false)}`
})


</script>

<template>

  <ToolBar v-if="returnData" :on-back="() => router.push('/app/returns')" :title="`Retoure ${returnData.return_number}`" :subtitle="subtitle">
    <template #right>
      <StateSwitch v-model="returnData.status_id" :returnModel="returnData" @update:modelValue="save" />

    </template>
  </ToolBar>

  <div v-if="returnData" class="flex gap-30 return-data items-start">
    <div class="left">
      <div class="row-top">
        <div class="customer-items">
          <PageCard title="Kunde" class="block-customer">
            <template #title>
              <button class="btn btn-sm btn-outline-secondary"><Pencil />Bearbeiten</button>
            </template>
            <CustomerInfo :customer="returnData.customer"/>
          </PageCard>

            <ItemsList :items="returnData.items" class="block-items"/>

        </div>

        <PageCard title="Entscheidung" class="block-decision">
          <ReturnDecision
              v-model="returnData.decision_id"
              @update:modelValue="save"
              :returnData="returnData"
          />
        </PageCard>
      </div>

      <PageCard title="Versand & Logistik" class="block-shipping">
        <ShipmentList :return_id="returnData.id" :items="returnData.shipments" @updated="load"/>
      </PageCard>
      <PageCard title="Erstattungen" class="block-refund"></PageCard>

    </div>
    <PageCard title="Akivitäten" class="history">
      <div class="history-items">
        <ReturnEvent v-for="event in returnData.events" :event="event"/>
      </div>
    </PageCard>
  </div>
</template>

<style lang="scss">
  .return-data{
    .left{
      flex: 1;
      display: flex;
      gap: 24px;
      flex-wrap: wrap;
      .row-top{
        width: 100%;
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
      }
      .customer-items{
        flex: 3 1 0;
        display: flex;
        gap: 24px;
        flex-direction: column;
      }
      .block-decision{
        flex: 4 1 0;
      }
      .block-shipping{
        flex: 1;
      }
      .block-refund{
        flex: 1;
      }
    }
    .history{
      width: 20%;
      min-width: 300px;

      .history-items{
        display: flex;
        flex-direction: column;
        gap: 12px;
      }
    }
  }
</style>