<script setup>
import {computed, ref} from "vue";
import {api} from "@/api/api.js";
import {useLookupStore} from "@/stores/lookups.js";
import {dateTimeStr} from "@/utils/datetime.js";
import {FilePenLine} from "lucide-vue-next";
import {useCurrencyStore} from "@/stores/currency.js";
import ShipmentStatusLabel from "@/components/ui/shipment/ShipmentStatusLabel.vue";
import InlineEditInput from "@/components/forms/InlineEditInput.vue";

const props = defineProps({
  item: {
    type: Object,
    required: true,
  }
})
const emit = defineEmits(["updated"])

const lookup = useLookupStore()
const currency = useCurrencyStore()


const directionLabel = computed(() => Number(props.item.direction) === 1 ? "vom Kunden" : "zum Kunden")
const payerLabel = computed(() => lookup.shipmentPayerOptions.find((x) => x.value === props.item.payer)?.label || "-")

const showIcon = ref(false)
const editTrack = ref(false)

const saveTrackingNumber = async (trackingNumber) => {
  editTrack.value = false
  try {
    await api.patch(`/api/returns/${props.item.return_id}/shipments/${props.item.id}`, {
      tracking_number: trackingNumber
    })
    emit("updated")
  } catch (error) {
    console.error("Failed to update shipment:", error)
  }
}



</script>

<template>
  <tr class="shipment-row">

    <td class="shipment-row__direction">{{ directionLabel }}</td>

    <td>
      <span class="text-small">{{ payerLabel }}</span>
      <div class="ws-nowrap font-bold">{{ currency.toActiveString(item.cost_cents) }}</div>
    </td>

    <td>
      <ShipmentStatusLabel :status="item.status" />
    </td>

    <td>
      <span >{{ item.carrier || '-'}}</span>
    </td>

    <td class="ws-nowrap">
      <InlineEditInput
          v-if="editTrack"
          size="xs"
          name="tracking_number"
          :value="item.tracking_number"
          @close="editTrack = false"
          @save="saveTrackingNumber"
      />
      <div v-else class="tracking_number flex gap-2" @mouseenter="showIcon=true" @mouseleave="showIcon=false">
        {{item.tracking_number || '-'}}
        <span class="icon" v-if="showIcon || !item.tracking_number">
           <FilePenLine  :size="12"  @click="editTrack = true"/>
        </span>
      </div>
    </td>

    <td class="text-muted text-small ">
      <div>{{ item?.created_by?.name || '-' }}</div>
      <div>{{ item.created_at?dateTimeStr(item.created_at, false):'-'}}</div>
    </td>
    <td><span class="text-small">{{ item.id }}</span></td>
  </tr>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.shipment-row {
  .tracking_number{
    cursor: pointer;
    position: relative;
    .icon{
      position: absolute;
      right: 0;
      background: #ffffffa3;
      padding: 1px;
    }
  }
}
</style>

