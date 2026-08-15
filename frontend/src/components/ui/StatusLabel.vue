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

hexToHsl(props.color)

const styles = computed(() => {
  return deriveBadgeColorsFromHsl(...hexToHsl(props.color))

})

const markerComponent = computed(() => markers[props.marker] || null)
const isDotMarker = computed(() => props.marker === 'dot')

</script>

<template>
  <div class="status status-label" :style="styles" v-if="mode === 'label'" title="Status">
    <div v-if="isDotMarker" class="dot" :style="{backgroundColor: styles.borderColor}"></div>
    <component v-else-if="markerComponent" :is="markerComponent" size="11" :style="{color: styles.color}"/>
    {{title}} <slot/>
  </div>
  <div class="status"  v-else-if="mode === 'bulb'" :style="{color: styles.color}">
    <div v-if="isDotMarker" class="dot" :style="{backgroundColor: styles.borderColor}"></div>
    <component v-else-if="markerComponent" :is="markerComponent" size="11" :style="{color: styles.color}"/>
    {{title}}
  </div>
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

    .dot{
      width: 6px;
      height: 6px;
      border-radius: 50%;
      flex-shrink: 0;
    }
  }


</style>
