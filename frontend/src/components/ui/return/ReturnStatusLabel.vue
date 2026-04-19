<script setup>

import {computed} from "vue";
import {hexToHsl, hslToCss} from "./../../../helpers/colors.js";

const props = defineProps({
  status: Object
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

hexToHsl(props.status.color)

const styles = computed(() => {
  return deriveBadgeColorsFromHsl(...hexToHsl(props.status.color))

})

</script>

<template>
  <div class="return-status-label" :style="styles">
    {{status.name}}
  </div>
</template>

<style scoped lang="scss">
@use "./../../../assets/scss/variables" ;
  .return-status-label{
    display: inline-flex;
    gap: 6px;
    font-size: 0.8rem;
    border: 1px solid;
    border-radius: 14px;
    padding: 2px 12px;
    font-weight: 500;

  }
</style>