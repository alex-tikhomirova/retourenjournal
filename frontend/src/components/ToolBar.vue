<script setup>
const props = defineProps({
  title: String,
  subtitle: String,
  onBack: { type: Function, default: null },
})
</script>

<template>
  <div class="flex justify-between tool-bar">
    <div class="flex items-center gap-20 left-side">
      <button class="" v-if="onBack" @click="onBack">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             class="lucide lucide-arrow-left">
          <path d="m12 19-7-7 7-7"></path>
          <path d="M19 12H5"></path>
        </svg>
      </button>
      <div v-if="title">
        <h3>{{title}}</h3>
        <p class="sub-title text-muted">{{subtitle}}</p>
      </div>

      <slot name="left"/>

    </div>
    <div class="flex gap-10 right-side">
      <slot name="right"/>

    </div>
  </div>
</template>

<style lang="scss">

.tool-bar{
  margin-bottom: 16px;
  .left-side{

  }
  .sub-title{

  }
}
</style>