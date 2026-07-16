<script setup>
import {dateTimeStr} from "@/utils/datetime.js";
import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
import ShipmentStatusLabel from "@/components/ui/shipment/ShipmentStatusLabel.vue";
import DecisionType from "../../../components/ui/return/DecisionType.vue";

  defineProps({
    event: Object
  })
</script>

<template>
  <div class="return-event">

    <div class="flex gap-6">
      <div class="title">{{event.event_title}}: </div>
    <div v-if="event.ref_type === 'status' && event.event_ref">
      <ReturnStatusLabel :status="event.event_ref"/>
    </div>
    <div v-else-if="event.ref_type === 'shipmentstatus' && event.event_ref">
      <ShipmentStatusLabel :status="event.event_ref"/>
    </div>
    <div v-else-if="event.ref_type === 'decision' && event.event_ref">
      <DecisionType :decision="event.event_ref" small/>
    </div>
    <div v-else class="font-bold">
      {{event.value}}
    </div>
    </div>
    <div class="text-muted stams">
      <span>{{dateTimeStr(event.created_at, false)}}</span>
      <span class="font-500">{{event.created_by?event.created_by.name:'-'}}</span>
    </div>
  </div>
</template>

<style  lang="scss">
  .return-event{
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.74rem;
    .title{
      font-weight: 500;
    }
    .stams{
      font-size: 90%;
      display: flex;
      gap: 12px;
    }
  }
</style>