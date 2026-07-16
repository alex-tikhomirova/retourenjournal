<script setup>
  defineProps({
    title: String
  })
</script>

<template>
  <div class="page-card">
    <div class="page-card-header" v-if="$slots.title || title">
      <div v-if="title" class="page-card-title">
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
@use "./../assets/scss/variables";
  .page-card{
    border: 1px solid variables.$border-color;
    border-radius: variables.$border-radius;
    background: variables.$background-color;
    .page-card-header{
      border-bottom: 1px solid variables.$border-color;
      border-top-right-radius: variables.$border-radius;
      border-top-left-radius: variables.$border-radius;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      .page-card-title{
        font-weight: 500;
        display: flex;
        gap: 7px;
        align-items: center;
      }
    }
    &.padded{
      .page-card-slot{
        padding: variables.$module-padding;
      }
    }

  }
</style>