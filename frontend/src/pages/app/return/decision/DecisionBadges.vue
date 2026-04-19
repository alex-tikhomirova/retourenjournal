<script setup>

import {CornerDownRight, CornerLeftUp, Undo} from "lucide-vue-next";

defineProps({
  decision: Object
})
</script>

<template>
  <div class="decision-badges">
    <div class="decision-badge" v-if="decision.requires_inbound_item">
      <CornerDownRight color="#138f41"  :size="12" />
      Eingang
    </div>
    <div class="decision-badge" v-if="decision.requires_refund">
      <Undo color="#c22121" :size="12"/>
      Erstattung
    </div>
    <div class="decision-badge" v-if="decision.requires_outbound_shipment">
      <CornerLeftUp color="#c22121" :size="12" />
      Ersatz
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "./../../../../assets/scss/variables" ;
.decision-badges{
  display: flex;
  gap: 6px;
  margin-top: 6px;
  .decision-badge{
    display: flex;
    gap: 6px;
    font-size: 0.8rem;
    border: 1px solid variables.$border-color;;
    background-color: variables.$head-bg-color;
    border-radius: 14px;
    padding: 1px 6px;
  }
}
</style>