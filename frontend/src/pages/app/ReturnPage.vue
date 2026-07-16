<script setup>
import ToolBar from "@/components/ToolBar.vue";
import {useRoute, useRouter} from "vue-router";
import {computed, ref, watch} from "vue";
import {api} from "@/api/api.js";
import PageCard from "@/components/PageCard.vue";
import ReturnEvent from "@/pages/app/return/ReturnEvent.vue";
import ReturnDecision from "./return/decision/ReturnDecision.vue";
import StateSwitch from "@/pages/app/return/StateSwitch.vue";
import ItemsList from "@/pages/app/return/ItemsList.vue";
import CustomerInfo from "@/pages/app/return/CustomerInfo.vue";
import {dateTimeStr} from "@/utils/datetime.js";
import ShipmentList from "@/pages/app/return/shipment/ShipmentList.vue";
import RefundList from "@/pages/app/return/refund/RefundList.vue";
import { Truck, Euro } from "lucide-vue-next";
import NumberBadge from "@/components/ui/NumberBadge.vue";
import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
import {useLookupStore} from "@/stores/lookups.js";

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


const opened = computed(() => returnData.value?.status?.kind !== 9)

const lookup = useLookupStore()

</script>

<template>

  <ToolBar v-if="returnData" :on-back="() => router.push('/app/returns')" >
    <template #left>
      <div class="">
        <h3>{{ `Retoure ${returnData.return_number}` }}</h3>
        <div class="sub-title text-muted flex gap-6">
          <ReturnStatusLabel :status="returnData.status" mode="bulb"/>
          ·
          <span class="flex">Zuletzt aktualisiert: {{ returnData.updated_at ? dateTimeStr(returnData.updated_at, false) : '—' }}</span>
        </div>
      </div>
    </template>
    <template #right>
      <StateSwitch v-model="returnData.status_id" :returnModel="returnData" @update:modelValue="save" />

    </template>
  </ToolBar>

  <div v-if="returnData" class="flex return-data items-start">
    <div class="left">
      <div class="row-top">
        <div class="customer-items">
          <CustomerInfo v-model="returnData.customer" :editable="opened"/>
          <ItemsList :items="returnData.items" class="block-items"/>
        </div>

        <div class="block-decision">
          <ReturnDecision
              :returnData="returnData"
              @updated="load"
              :editable="opened"
          />
        </div>
      </div>

        <PageCard class="block-shipping" >
          <template #title>
            <div class="page-card-title">
              <Truck :size="18"/>
              Versand & Logistik
              <NumberBadge :value="returnData.shipments.length"/>
            </div>
          </template>
          <ShipmentList  :return_id="returnData.id" :items="returnData.shipments" @updated="load" :editable="opened"/>
        </PageCard>
        <PageCard class="block-refund">
          <template #title>
            <div class="page-card-title">
              <Euro :size="18"/>
              Erstattungen
              <NumberBadge :value="returnData.refunds.length"/>
            </div>
          </template>
          <RefundList :return_id="returnData.id" :items="returnData.refunds" @updated="load" :editable="opened"/>
        </PageCard>
      </div>


    <PageCard title="Historie" class="history">
      <div class="history-items">
        <ReturnEvent v-for="event in returnData.events" :event="event"/>
      </div>
    </PageCard>
  </div>
</template>

<style lang="scss">
  @use "@/assets/scss/variables" ;
  .return-data{
    gap: variables.$module-gap;
    .left{
      flex: 1;
      display: flex;
      gap: variables.$module-gap;
      flex-wrap: wrap;
      align-items: flex-start;
      .row-top{
        width: 100%;
        display: flex;
        gap: variables.$module-gap;
        flex-wrap: wrap;
      }
      .customer-items{
        flex: 3 1 0;
        display: flex;
        gap: variables.$module-gap;;
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
        margin: 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
      }
    }
  }
</style>