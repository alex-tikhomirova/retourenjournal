<script setup>
  import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";

  defineProps({
    status: Object,
  })
</script>

<template>
  <div class="next-status color-card primary" v-if="status">
    <div class="e-title text-muted">
      Nächster Status:
    </div>
    <div class="e-content">
      <ReturnStatusLabel :status="status"/> -
      <span class="text-muted">{{status.description}}</span>
    </div>
  </div>
</template>

<style scoped lang="scss">

</style>