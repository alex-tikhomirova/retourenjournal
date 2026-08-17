<script setup>

import StatusLabel from "@/components/ui/StatusLabel.vue";

const props = defineProps({
  status: {
    type: Object,
    required: true,
  },
});

</script>

<template>
  <StatusLabel class="shipment-status" :title="status?.name" :color="status?.color" marker="truck">
    <template v-if="$slots.default" #default>
      <slot/>
    </template>
  </StatusLabel>
</template>

<style scoped lang="scss">

</style>
