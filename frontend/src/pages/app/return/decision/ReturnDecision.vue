<script setup>

import {useLookupStore} from "@/stores/lookups.js";
import {computed, ref} from "vue";
import {Check, X, ChevronDown, ChevronUp} from "lucide-vue-next";
import DecisionCard from "./DecisionCard.vue";
import DecisionResult from "./DecisionResult.vue";
import CurrentDecision from "@/pages/app/return/decision/CurrentDecision.vue";


const value = defineModel({
  type: Number
})

const props = defineProps({
  returnData: Object,
})

const lookup = useLookupStore()


const selected = ref(0)
const selectedGroup = ref('')

const current = computed(() => lookup.returnDecision(selected.value))

</script>

<template>
  <div class="return-decision">
    <CurrentDecision v-if="returnData.decision_id" :decision_id="returnData.decision_id"/>
    <div class="decision-select" v-else>
      <div class="decision-accordeon" :class="{wide: !selected}">
        <div class="group">
          <button class="select-btn approve"
                  @click="selectedGroup === 'approve' ? selectedGroup = '' : selectedGroup = 'approve'"
                  :class="{active: selectedGroup === 'approve'}">
            <span class="title">
              <Check color="#138f41" :size="24"/>
              Genehmigung
            </span>
            <ChevronUp v-if="selectedGroup === 'approve'"/>
            <ChevronDown v-else/>
          </button>
          <div class="cards" v-if="selectedGroup === 'approve'">
            <template v-for="decision in lookup.returnDecisions">
              <DecisionCard
                  v-if="decision.outcome === 'approve'"
                  :key="decision.id"
                  :item="decision"
                  v-model="selected"
              />
            </template>
          </div>
        </div>

        <div class="group">
          <button class="select-btn reject"
                  @click="selectedGroup === 'reject' ? selectedGroup = '' : selectedGroup = 'reject'" :class="{active: selectedGroup === 'reject'}">
            <span class="title">
              <X color="#c22121" :size="24"/>
              Ablehnung
            </span>
            <ChevronUp v-if="selectedGroup === 'reject'"/>
            <ChevronDown v-else/>
          </button>
          <div class="cards" v-if="selectedGroup === 'reject'">
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
      </div>
      <div class="result-area" v-if="current">
        <DecisionResult  :decision="current" :returnData="returnData" @reset="selected = 0" @confirm="value = selected"/>
      </div>
    </div>



  </div>

</template>

<style scoped lang="scss">
@use "./../../../../assets/scss/variables";

.decision-select {
  display: flex;
  .decision-accordeon {
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 3;

    .group {
      border: 1px solid variables.$border-color;
      border-radius: variables.$border-radius;

      .select-btn {
        display: flex;
        width: 100%;
        padding: 12px 16px;
        justify-content: space-between;
        align-items: center;
        border-radius: variables.$border-radius;

        .title {
          display: flex;
          gap: 12px;
          font-weight: 600;
        }
        &:hover{
          background-color: variables.$head-bg-color;
        }
        &.active{
          border-bottom: 1px solid variables.$border-color;
          &.approve{
            background-color: variables.$bg-color-primary;

          }
          &.reject{
            background-color: variables.$bg-color-danger;
          }
        }
      }

      .cards{
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 6px 12px;
      }
    }
  }
  .result-area{
    flex: 4;
    border-left: 1px solid variables.$border-color;
    padding: 18px;
  }

}









</style>