<script setup>
import RadioButton from "@/components/forms/RadioButton.vue";
import DecisionBadges from "@/pages/app/return/decision/DecisionBadges.vue";

defineProps({
  item: Object,
})
const model = defineModel({
  type: Number
})



</script>

<template>
  <div class="decision-card" @click="model = item.id">
    <div class="radio">
      <RadioButton v-model="model" :value="item.id" name="decision"/>
    </div>
    <div class="info">
      <div class="title">{{ item.name }}</div>
      <div class="description text-muted text-small">{{ item.description }}</div>
      <DecisionBadges :decision="item"/>
    </div>
  </div>
</template>

<style  lang="scss">
  @use "@/assets/scss/variables" ;
  .decision-card{
    border: 1px solid variables.$border-color;
    padding: 6px 12px;
    border-radius: variables.$border-radius;
    display: flex;
    gap: 12px;
    align-items: center;
    cursor: pointer;
    margin: 6px 0;
    .title{
      font-weight: 500;
    }


  }
</style>