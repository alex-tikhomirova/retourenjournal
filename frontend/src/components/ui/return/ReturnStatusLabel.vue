<script setup>

import StatusLabel from "@/components/ui/StatusLabel.vue";

const props = defineProps({
  status: Object,
  mode: {
    type: String,
    default: 'label'
  }
})

</script>

<template>
  <StatusLabel class="return-status" :title="status?.name" :color="status?.color" marker="dot" :mode="mode">
    <template v-if="$slots.default" #default>
      <slot/>
    </template>
  </StatusLabel>
</template>
