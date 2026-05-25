<script setup>
import {Check, X} from "lucide-vue-next";

  defineProps({
    item: Object
  })
</script>

<template>
  <Check color="#138f41" :size="18" v-if="item?.outcome === 'approve'"/>
  <X color="#c22121" :size="18" v-if="item?.outcome === 'reject'"/>
</template>

<style scoped lang="scss">

</style>