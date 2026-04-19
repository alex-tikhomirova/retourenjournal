<script setup>
import {computed} from "vue";
import {useCurrencyStore} from "./../../../stores/currency.js";
import PageCard from "./../../../components/PageCard.vue";

const props = defineProps({
  items: Array
})

const currency = useCurrencyStore()

const sum = computed(() => props.items.reduce((total, i) => total + currency.toActive(i.unit_price_cents, i.currency), 0))
</script>

<template>
  <PageCard :title="`Artikel (${items.length})`" >
    <template #title>
      <strong>{{ currency.toActiveString(sum) }}</strong>
    </template>
  <table class="table grid-table items-list-table">
    <tbody>
    <tr v-for="item in items">
      <td class="sku">{{item.sku}}</td>
      <td class="name">{{item.item_name}} ({{item.quantity}})</td>
      <td class="price">{{currency.toActiveString(item.unit_price_cents, item.currency)}}</td>
    </tr>
    </tbody>
  </table>
  </PageCard>
</template>

<style scoped lang="scss">
.items-list-table{
  tr:first-child > td{
    border-top: none;
  }
  .sku{
    font-weight: 500;
  }
  .name{}
  .price{
    text-align: right;
  }
}
</style>