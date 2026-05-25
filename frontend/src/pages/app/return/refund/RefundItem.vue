<script setup>
import {useLookupStore} from "@/stores/lookups.js";
import {useCurrencyStore} from "@/stores/currency.js";
import {dateTimeStr} from "@/helpers/datetime.js";

const props = defineProps({
  item: {
    type: Object,
    required: true,
  }
})
const emit = defineEmits(["updated"])

const lookup = useLookupStore()
const currency = useCurrencyStore()
</script>

<template>
  <tr class="refund-row">
    <td>{{item.reference}}</td>
    <td>{{currency.toActiveString(item.amount_cents, item.currency)}}</td>
    <td></td>
    <td class="text-muted text-small ">
      <div>{{ item?.created_by?.name || '-' }}</div>
      <div>{{ item.created_at?dateTimeStr(item.created_at, false):'-'}}</div>
    </td>
    <td class="text-muted text-small ">
      <div>{{ item.processed_at?dateTimeStr(item.processed_at, false):'-'}}</div>
    </td>
  </tr>
</template>

<style scoped lang="scss">

</style>