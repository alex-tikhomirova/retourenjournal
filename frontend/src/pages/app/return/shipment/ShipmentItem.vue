<script setup>
import {computed, ref, watch} from "vue";
import {api} from "@/api/api.js";
import {useLookupStore} from "@/stores/lookups.js";
import {dateTimeStr} from "@/utils/datetime.js";
import {useCurrencyStore} from "@/stores/currency.js";
import ShipmentStatusLabel from "@/components/ui/shipment/ShipmentStatusLabel.vue";
import InlineEditInput from "@/components/forms/InlineEditInput.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import {useFormErrors} from "@/utils/useFormErrors.js";
import FormFieldText from "@/components/forms/FormFieldText.vue";

const props = defineProps({
  item: {
    type: Object,
    required: true,
  }
})

const emit = defineEmits(["updated"])

const lookup = useLookupStore()
const currency = useCurrencyStore()
const {
  clearErrors,
  errorText,
  handleRequest,
} = useFormErrors()

const formData = ref({...props.item})
const statusOptions = computed(() => lookup.shipmentStatuses.map(i => ({label: i.name, value: i.id})))
const carrierOptions = lookup.shipmentCarrierOptions
const editMode = ref(null)


const directionLabel = computed(() => Number(props.item.direction) === 1 ? "vom Kunden" : "zum Kunden")
const payerLabel = computed(() => lookup.shipmentPayerOptions.find((x) => x.value === props.item.payer)?.label || "-")

watch(
    () => props.item,
    (item) => {
      formData.value = {...item}
    }
)
watch(editMode, () => {
  formData.value = {...props.item}
  clearErrors()
})

const save = () => handleRequest(async () => {
  await api.patch(`/api/shipments/${props.item.id}`, {
    carrier: formData.value.carrier,
    tracking_number: formData.value.tracking_number,
    status_id: formData.value.status_id,
  })
  editMode.value = null
  emit("updated")
})

</script>

<template>
  <tr class="shipment-row">

    <td class="shipment-row__direction">{{ directionLabel }}</td>

    <td>
      <span class="text-small">{{ payerLabel }}</span>
      <div class="ws-nowrap font-bold">{{ currency.toActiveString(item.cost_cents) }}</div>
    </td>

    <td>
      <button class="btn btn-link btn-sm" @click.stop.prevent="editMode = 'status'" v-if="editMode !== 'status'">
        <ShipmentStatusLabel :status="item.status"></ShipmentStatusLabel>
      </button>
      <InlineEditInput
          v-else
          size="xs"
          :show-actions="false"
          @close="editMode = null"
      >
        <FormFieldSelect name="status_id" v-model="formData.status_id" :options="statusOptions" @update:modelValue="save" class="input-xs"/>
      </InlineEditInput>
    </td>

    <td v-if="editMode !== 'tracking'">
      <button class="btn btn-link btn-sm" @click.stop.prevent="editMode = 'carrier'" v-if="editMode !== 'carrier'">
        <span class="text-underline">{{ item.carrier || '+ Dienstleister' }}</span>

      </button>
      <InlineEditInput
          v-else
          size="xs"
          :show-actions="false"
          @close="editMode = null"
      >
        <FormFieldSelect
            name="carrier"
            v-model="formData.carrier"
            :options="carrierOptions"
            @update:modelValue="save"
            class="input-xs"
        />
      </InlineEditInput>
    </td>

    <td :colspan="editMode === 'tracking' ? 3 : 1">
      <button class="btn btn-link btn-sm" @click.stop.prevent="editMode = 'tracking'" v-if="editMode !== 'tracking'">
        <span class="text-underline">{{ item.tracking_number || '+ Tracking' }}</span>
      </button>
      <div class="flex gap-12"  v-else>
        {{ item.carrier || '—'}}
        <InlineEditInput
            size="xs"
            @close="editMode = null"
            @save="save"
        >
          <FormFieldText
              v-model="formData.tracking_number"
              name="tracking_number"
              class="input-xs"
          />
        </InlineEditInput>
      </div>

    </td>

    <td class="text-muted text-small" v-if="editMode !== 'tracking'">
      <div class="created-by">{{ item?.created_by?.name || '—' }}</div>
      <div>{{ item.created_at?dateTimeStr(item.created_at, false):'-'}}</div>
    </td>
    <td>
      <span class="text-small">{{ item.shipment_number }}</span>
      <div v-if="errorText" class="table-subrow-message text-danger text-small">{{errorText}}</div>
    </td>
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
  .created-by{
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}
</style>

