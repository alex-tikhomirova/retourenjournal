<script setup>

  defineProps({
    decision: Object
  })
</script>

<template>
    <span class="decison-type color-card compact success text-success ">
       {{decision.outcome === 'approve' ? 'Genehmigt' : 'Abgelehnt'}}
    </span>
</template>

<style scoped lang="scss">
@use "./../../../assets/scss/variables";
@use "./../../../assets/scss/colored-cards";
  .decison-type.color-card{
    margin: 0;

  }
</style>