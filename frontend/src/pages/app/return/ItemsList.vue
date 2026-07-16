<script setup>
import {computed} from "vue";
import {useCurrencyStore} from "@/stores/currency.js";
import PageCard from "./../../../components/PageCard.vue";
import NumberBadge from "../../../components/ui/NumberBadge.vue";

const props = defineProps({
  items: Array
})

const currency = useCurrencyStore()

const sum = computed(() => props.items.reduce(
    (total, i) => (i.unit_price_cents !== null)?(Number(total) + currency.toActive(i.unit_price_cents, i.currency)):total
    , null)
)
</script>

<template>
  <PageCard  >
    <template #title>
      <div class="page-card-title">
        Artikel
        <NumberBadge :value="props.items.length"/>
      </div>
      <span class="font-bold">{{ currency.toActiveString(sum) }}</span>
    </template>
  <table class="table grid-table items-list-table">
    <tbody>
    <tr v-for="item in items">
      <td class="pos">{{item.line_no}}</td>
      <td class="sku">{{item.sku ?? '—'}}</td>
      <td class="name text-small">{{item.item_name}} ({{item.quantity}})</td>
      <td class="serial text-small text-muted">{{item.serial ? `S/N: ${item.serial}`: '—'}}</td>
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