<script setup>
  defineProps({
    value: {
      type: [Number, String],
      default: 0
    }
  })
</script>

<template>
  <span class="badge">
    {{value}}
  </span>
</template>

<style scoped lang="scss">
  @use "./../../assets/scss/variables";
  .badge {
    background-color: variables.$color-disabled-bg;
    color: variables.$color-disabled-fg;

    padding: 0 6px;
    border-radius: 999px;
  }
</style>