<script setup>

import {computed} from "vue";
import {useLookupStore} from "@/stores/lookups.js";
import DecisionNextStatus from "@/pages/app/return/decision/DecisionNextStatus.vue";
import {Truck, BanknoteArrowUp, PackagePlus, Plus} from "lucide-vue-next";
import DecisionType from "@/components/ui/return/DecisionType.vue";

const props = defineProps({
  decision_id: Number,
})
const lookup = useLookupStore()
const decision = computed(() => lookup.returnDecision(props.decision_id))

</script>

<template>
  <div class="decision-current flex gap-12" >
    <div class="selected-decision" v-if="decision">
      <DecisionType :decision="decision"/>
      <div>
        <div class="font-bold">{{ decision.name }}</div>
        <div class=" text-muted text-small">{{ decision.description }}</div>
      </div>

    </div>
    <div class="decision-actions">
      <DecisionNextStatus :status="decision?.nextStatus"/>
      <div class="flex justify-between gap-6">
        <div v-if="decision.requires_inbound_item"
             class="color-card compact primary text-primary text-small flex gap-6">
          <PackagePlus :size="14"/>
          Wareneingang erforderlich
        </div>
        <Plus color="#9CA3AF" size="14" v-if="decision.requires_refund" />
        <div v-if="decision.requires_refund" class="color-card compact danger-danger text-danger text-small flex gap-6">
          <BanknoteArrowUp :size="14"/>
          Rückerstattung erforderlich
        </div>
        <Plus color="#9CA3AF"  size="14" v-if="decision.requires_outbound_shipment" />
        <div v-if="decision.requires_outbound_shipment"
             class="color-card compact success text-success text-small flex gap-6">
          <Truck :size="14"/>
          Ersatzversand erforderlich
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/colored-cards" ;
  .decision-current {
    display: flex;
    gap: 24px;
    align-items: flex-start;
    >*{
      flex: 1;
      margin: 18px;
    }
    .selected-decision{
      display: flex;
      flex-direction: column;
      gap: 12px;
      align-items: flex-start;
    }

  }
.decision-actions{
  display: flex;
  flex-direction: column;
  gap: 12px;

}
</style>