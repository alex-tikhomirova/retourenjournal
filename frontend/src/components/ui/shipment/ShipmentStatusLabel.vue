<script setup>
import { computed } from "vue";
import { Truck } from "lucide-vue-next";

const props = defineProps({
  status: {
    type: Object,
    required: true,
  },
});

const STATUS_THEME = {
  created: {
    bg: "#eef4ff",
    text: "#1f3b79",
    border: "#bfd2ff",
    accent: "#3f6ad8",
  },
  shipped: {
    bg: "#edf9ff",
    text: "#0f4e66",
    border: "#b8e4f7",
    accent: "#1a9ac6",
  },
  in_transit: {
    bg: "#fff8e8",
    text: "#6f4a08",
    border: "#f6db9c",
    accent: "#d79212",
  },
  delivered: {
    bg: "#ecf9f1",
    text: "#175c3c",
    border: "#bde8cf",
    accent: "#2faa6a",
  },
  returned: {
    bg: "#f4f3ff",
    text: "#41358c",
    border: "#d7d1ff",
    accent: "#6a56de",
  },
  cancelled: {
    bg: "#fdf1f3",
    text: "#7e2634",
    border: "#f3c3cc",
    accent: "#d8506a",
  },
};

const defaultTheme = {
  bg: "#f3f5f8",
  text: "#3f4a5a",
  border: "#d8dfe8",
  accent: "#79869a",
};

const theme = computed(() => {
  const code = props.status?.code;
  return STATUS_THEME[code] || defaultTheme;
});

const styles = computed(() => ({
  backgroundColor: theme.value.bg,
  color: theme.value.text,
  borderColor: theme.value.border,
  "--shipment-accent": theme.value.accent,
}));
</script>

<template>
  <div class="shipment-status-label" :style="styles">
    <Truck class="shipment-status-label__icon" :size="16" :stroke-width="2.2" aria-hidden="true" />
    <span>{{ status?.name || "Unknown" }}</span>
  </div>
</template>

<style scoped lang="scss">
.shipment-status-label {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 4px 10px;
  border: 1px solid;
  border-radius: 8px;
  font-size: 0.74rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.shipment-status-label__icon {
  color: var(--shipment-accent);
  flex-shrink: 0;
}
</style>