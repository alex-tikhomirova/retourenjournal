<script setup>

defineProps({
  customer: Object
})
</script>

<template>
  <div>{{ customer.name }}</div>
  <div>{{ customer.phone }}</div>
  <div>{{ customer.email }}</div>
</template>

<style scoped lang="scss">

</style>