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
  <StatusLabel class="refund-status" :title="status?.name" :color="status?.color" marker="euro">
    <slot/>
  </StatusLabel>
</template>

<style scoped lang="scss">

</style>