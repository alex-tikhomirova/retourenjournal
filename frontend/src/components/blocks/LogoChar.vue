<script setup>
  defineProps({
    size: {
      type: Number,
      default: 50,
    },
    fill: {
      type: String,
      default: '#256FA8'
    }
  })
</script>

<template>
  <svg :width="size" :height="size" viewBox="0 0 255 275" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path
        d="M151.5 6.10352e-05C103.5 -0.000159834 0 0.00021501 0 0.00021501L3.9693e-05 275H62V191L24 152.5L63 114V53.5002H143C157.51 53.5002 169.5 67.2256 169.5 84.5001V95.5001C169.5 113 156 126.5 143 126.5H66.5L41 152.5L67.5 179H91L164.5 275H254.5L172 169C210.5 165.5 234.5 131.5 234.5 100.5V75.5001C234.5 41.0001 203 0.000298009 151.5 6.10352e-05Z"
        :fill="fill"
    />
  </svg>
</template>

<style scoped>

</style>