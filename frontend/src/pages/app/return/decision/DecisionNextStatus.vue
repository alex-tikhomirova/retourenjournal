<script setup>
  import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
  import {ArrowRight} from "lucide-vue-next";
  defineProps({
    status: Object,
  })
</script>

<template>
  <div class="next-status color-card primary" v-if="status">
    <div class="e-title text-muted">
      <ArrowRight  size="14"/> Nächster Status:
    </div>
    <div class="e-content">
      <ReturnStatusLabel :status="status"/>
      <div class="text-muted text-small">{{status.description}}</div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.next-status{
  display: flex;
  flex-direction: column;
  gap: 12px;
  .e-title{
    display: flex;
    gap: 6px;
    align-items: center;

  }
  .e-content{
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    gap: 6px;
  }
}

</style>