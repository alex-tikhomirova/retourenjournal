<script setup>

import {computed} from "vue";
import {hexToHsl, hslToCss} from "@/utils/colors.js";
import { Truck, Circle, Euro } from "lucide-vue-next";

const markers = {
  circle: Circle,
  Circle,
  truck: Truck,
  Truck,
  euro: Euro,
  Euro,
}

const props = defineProps({
  title: {
    type: String,
    default: 'Unknown'
  },
  color: {
    type: String,
    default: '#57534e'
  },
  marker: {
    type: String,
    default: ''
  },
  mode: {
    type: String,
    default: 'label'
  }
})

const deriveBadgeColorsFromHsl = (h, s, l) => {
  const isLightBg = l > 70;

  const text = isLightBg
      ? {
        h,
        s: Math.max(s - 8, 18),
        l: Math.max(l - 52, 22),
      }
      : {
        h,
        s: Math.max(s - 4, 18),
        l: Math.min(l + 52, 92),
      };

  const border = isLightBg
      ? {
        h,
        s: Math.max(s - 4, 20),
        l: Math.max(l - 22, 35),
      }
      : {
        h,
        s: Math.max(s - 2, 20),
        l: Math.min(l + 22, 82),
      };

  return {
    backgroundColor: hslToCss(h, s, l),
    color: hslToCss(text.h, text.s, text.l),
    borderColor: hslToCss(border.h, border.s, border.l),
  };
};

const styles = computed(() => {
  return deriveBadgeColorsFromHsl(...hexToHsl(props.color))
})

const element = computed(() => props.mode === 'button' ? 'button' : 'div')
const classes = computed(() => ({
  'status-label': props.mode === 'label',
  'status-button': props.mode === 'button',
  'btn': props.mode === 'button',
}))
const componentStyles = computed(() => {
  if (props.mode === 'bulb') {
    return {color: styles.value.color}
  }

  if (props.mode === 'button') {
    return {
      '--status-background': styles.value.backgroundColor,
      '--status-color': styles.value.color,
      '--status-border': styles.value.borderColor,
    }
  }

  return styles.value
})

const markerComponent = computed(() => markers[props.marker] || null)
const isDotMarker = computed(() => props.marker === 'dot')

</script>

<template>
  <component
      :is="element"
      class="status"
      :class="classes"
      :style="componentStyles"
      :type="mode === 'button' ? 'button' : undefined"
      title="Status"
  >
    <div v-if="isDotMarker" class="dot" :style="{backgroundColor: styles.borderColor}"></div>
    <component v-else-if="markerComponent" :is="markerComponent" size="11" :style="{color: styles.color}"/>
    <slot>{{title}}</slot>
  </component>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" ;

  .status{
    display: inline-flex;
    gap: 6px;
    align-items: center;
    &.transparent{
      background-color: transparent !important;
    }
    &.status-label{
      font-size: 11px;
      border: 1px solid;
      border-radius: variables.$border-radius;
      padding: 2px 8px;
      font-weight: 500;
    }
    &.status-button {
      background: var(--status-background);
      border-color: var(--status-border);
      color: var(--status-color);

      &:hover:not(:disabled):not(.is-disabled) {
        background: color-mix(in srgb, var(--status-background), white 12%);
        border-color: color-mix(in srgb, var(--status-border), white 12%);
      }

      &:active:not(:disabled):not(.is-disabled),
      &.is-active {
        background: color-mix(in srgb, var(--status-background), black 10%);
        border-color: color-mix(in srgb, var(--status-border), black 10%);
        transform: translateY(1px);
      }

      &:focus-visible {
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--status-border), transparent 72%);
      }
    }

    .dot{
      width: 6px;
      height: 6px;
      border-radius: 50%;
      flex-shrink: 0;
    }
  }


</style>
