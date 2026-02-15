<script setup>
  defineProps({
    title: String
  })
</script>

<template>
  <div class="page-card">
    <div class="page-card-header">
      <div class="page-card-title">
        {{ title }}
      </div>
      <slot name="title"/>
    </div>
    <div class="page-card-slot">

        <slot/>

    </div>
  </div>
</template>

<style lang="scss">
@use "@/assets/scss/variables";
  .page-card{
    border: 1px solid variables.$border-color;
    border-radius: variables.$border-radius;
    .page-card-header{
      background-color: variables.$head-bg-color;
      border-bottom: 1px solid variables.$border-color;
      border-top-right-radius: variables.$border-radius;
      border-top-left-radius: variables.$border-radius;
      height: 48px;
      padding-left: 20px;
      padding-right: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      .page-card-title{
        font-weight: 500;
        font-size: 1rem;

      }
    }

    .page-card-slot{
      padding: 20px;
    }
  }
</style>