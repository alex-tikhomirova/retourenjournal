<script setup>

import {useLookupStore} from "@/stores/lookups.js";
import {computed, ref} from "vue";
import {Check, X} from "lucide-vue-next";
import DecisionCard from "@/pages/app/return/DecisionCard.vue";
import DecisionResult from "@/pages/app/return/decision/DecisionResult.vue";
import DecisionBadges from "@/pages/app/return/decision/DecisionBadges.vue";


const value = defineModel({
  type: Number
})

const props = defineProps({
  returnData: Object,
})

const lookup = useLookupStore()


const selected = ref(0)

const current = computed(() => lookup.returnDecision(selected.value))

</script>

<template>
  <div class="return-decision">


    <div class="decision-current" v-if="returnData.decision">
      <div class="selected-decision entity">
        <h4 class="e-title text-muted">
           Du hast gewählt:
        </h4>
        <div class="e-content">
          <div class="d-name">{{ returnData.decision.name }}</div>
          <DecisionBadges :decision="returnData.decision"/>
        </div>
      </div>
    </div>
    <div class="decision-cols" v-else>
      <div class="col">
        <div class="title">
          <Check color="#138f41" :size="24"/> Genehmigung:
        </div>
        <div class="cards" >
          <template v-for="decision in lookup.returnDecisions">
            <DecisionCard
                v-if="decision.outcome === 'approve'"
                :key="decision.id"
                :item="decision"
                v-model="selected"
            />
          </template>
        </div>
        <div class="title">
          <X color="#c22121" :size="24"/> Ablehnung:
        </div>
        <div class="cards" >
          <template v-for="decision in lookup.returnDecisions">
            <DecisionCard
                v-if="decision.outcome === 'reject'"
                :key="decision.id"
                :item="decision"
                v-model="selected"
            />
          </template>
        </div>
      </div>
      <div class="col">
        <DecisionResult v-if="current" :decision="current" :returnData="returnData" @reset="selected = 0" @confirm="value = selected"/>
        <div v-else class="text-muted">
          keine wahle
        </div>

      </div>
    </div>




  </div>

</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";
.decision-cols{
  display: flex;
  .col{
    padding-left: 24px;
    padding-right: 0;

    &:first-child{
      border-right: 1px solid variables.$border-color;
      padding-right: 24px;
      padding-left: 0;
      flex: 2 1 0;
    }
    flex: 3 1 0;
    .title{
      display: flex;
      gap: 12px;
      font-size: 1.2rem;
      margin-bottom: 12px;
      font-weight: 500;
    }
    .cards{
      margin-bottom: 24px;
    }
  }
}
</style>