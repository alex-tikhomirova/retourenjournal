<script setup>

defineProps({
  customer: Object
})
</script>

<template>
  <div class="customer-info">
      <table class="table">
        <tbody>
        <tr>
          <td>Name:</td>
          <td>{{ customer.name }}</td>
        </tr>
        <tr>
          <td>Telefon:</td>
          <td>{{ customer.phone }}</td>
        </tr>
        <tr>
          <td>E-mail:</td>
          <td>{{ customer.email }}</td>
        </tr>
        </tbody>
      </table>
  </div>

</template>

<style scoped lang="scss">

</style>