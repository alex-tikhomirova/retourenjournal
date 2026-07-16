<script setup>
  import {Check, X} from "lucide-vue-next";
  defineProps({
    decision: Object,
    small: {
      type: Boolean,
      default: false
    }
  })
</script>

<template>
  <span v-if="decision.outcome === 'approve'" :class="{'sub-compact': small}" class="decison-type color-card transparent success text-success text-small">
    <Check size="12"/> Genehmigt
  </span>
  <span v-else :class="{'sub-compact': small}"  class="decison-type color-card danger transparent text-danger text-small ">
     <X size="12"/> Abgelehnt
  </span>
</template>

<style scoped lang="scss">
@use "./../../../assets/scss/variables";
@use "./../../../assets/scss/colored-cards";
  .decison-type{
    margin: 0;
    display: flex;
    gap: 6px;
    align-items: center;
    border: none;
  }
</style>