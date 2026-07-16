<script setup>

import {PackagePlus, Repeat, BanknoteArrowUp} from "lucide-vue-next";

defineProps({
  decision: Object
})
</script>

<template>

  <div class="decision-badges">
    <div class="color-card sub-compact primary text-primary text-small flex gap-6" v-if="decision.requires_inbound_item">
      <PackagePlus :size="12"/> Eingang
    </div>
    <div class="color-card sub-compact danger text-danger text-small flex gap-6" v-if="decision.requires_refund">
      <BanknoteArrowUp :size="12"/>
      Erstattung
    </div>
    <div class="color-card sub-compact success text-success text-small flex gap-6" v-if="decision.requires_outbound_shipment">
      <Repeat :size="12" />
      Ersatz
    </div>
  </div>
</template>

<style scoped lang="scss">

@use "@/assets/scss/variables" ;
@use "@/assets/scss/colored-cards";
.decision-badges{
  display: flex;
  gap: 6px;
}
</style>