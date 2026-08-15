<script setup>
import {useLookupStore} from "@/stores/lookups.js";
import {useCurrencyStore} from "@/stores/currency.js";
import {dateTimeStr} from "@/utils/datetime.js";
import RefundStatusLabel from "@/components/ui/refund/RefundStatusLabel.vue";
import {computed, ref, watch} from "vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import {api} from "@/api/api.js";
import {useFormErrors} from "@/utils/useFormErrors.js";
import InlineEditInput from "@/components/forms/InlineEditInput.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormFieldDateTime from "@/components/forms/FormFieldDateTime.vue";
import {debounce} from "@/utils/debounce.js";

const props = defineProps({
  item: {
    type: Object,
    required: true,
  }
})
const emit = defineEmits(["updated"])

const lookup = useLookupStore()
const currency = useCurrencyStore()
const editMode = ref(null)
const statusElement = ref(null)
const statusOptions = computed(() => lookup.refundStatuses.map(i => ({label: i.name, value: i.id})))

const formData = ref({...props.item})
const {
  clearErrors,
  errorText,
  handleRequest,
} = useFormErrors()

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
  await api.patch(`/api/refunds/${props.item.id}`, {
    reference: formData.value.reference,
    status_id: formData.value.status_id,
    processed_at: formData.value.processed_at,
  })
  editMode.value = null
  emit("updated")
})
const saveProcessedAt = debounce(save, 500)
</script>

<template>
  <tr class="refund-row">

    <td :colspan="editMode === 'reference' ? 2 : 1">
      <div class="ws-nowrap font-bold">{{ currency.toActiveString(item.amount_cents, item.currency) }}</div>
      <button class="btn btn-link btn-sm" @click.stop.prevent="editMode = 'reference'" v-if="editMode !== 'reference'">
        <span class="text-underline">{{ item.reference || '+ Referenz' }}</span>
      </button>
      <div class="flex gap-12"  v-else>
        <InlineEditInput
            size="xs"
            @close="editMode = null"
            @save="save"
        >
          <FormFieldText
              placeholder="z.B. PayPal-ID, Gutschrift oder Bankreferenz"
              v-model="formData.reference"
              name="reference"
              class="input-xs"
          />
        </InlineEditInput>
      </div>
    </td>
    <td ref="statusElement" v-if="editMode !== 'reference'">
      <button class="btn btn-link btn-sm" @click.stop.prevent="editMode = 'status'" v-if="editMode !== 'status'">
        <RefundStatusLabel :status="item.status"></RefundStatusLabel>
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
    <td class="text-muted text-small ">
      <div>{{ item?.created_by?.name || '—' }}</div>
      <div>{{ item.created_at?dateTimeStr(item.created_at, false):'-'}}</div>
    </td>
    <td class="text-muted text-small ">
      <div v-if="!item.processed_at">—</div>
      <button class="btn btn-link btn-sm" @click.stop.prevent="editMode = 'processed'" v-else-if="item.processed_at && (editMode !== 'processed')">
        <span class="text-underline">{{ dateTimeStr(item.processed_at, false)}}</span>
      </button>
      <InlineEditInput
          v-else
          size="xs"
          :show-actions="false"
          @close="editMode = null"
      >
        <FormFieldDateTime
            name="processed_at"
            v-model="formData.processed_at"
            placeholder="Datum und Uhrzeit auswählen"
            inputClass="input-xs"
            @update:modelValue="saveProcessedAt"
        />
      </InlineEditInput>

    </td>
    <td>
      <span class="text-small">{{item.refund_number}}</span>
      <div v-if="errorText" class="table-subrow-message text-danger text-small">{{errorText}}</div>
    </td>
  </tr>
</template>

<style scoped lang="scss">

</style>
