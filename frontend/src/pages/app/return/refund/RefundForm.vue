<script setup>

import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
import {useLookupStore} from "@/stores/lookups.js";
import {computed, ref} from "vue";
import FormFieldCurrency from "@/components/forms/FormFieldCurrency.vue";
import FormFieldDateTime from "@/components/forms/FormFieldDateTime.vue";
import {Save, X} from "lucide-vue-next";
import {useFormErrors} from "@/utils/useFormErrors.js";
import {api} from "@/api/api.js";

const props = defineProps({
  return_id: {
    type: Number,
    required: true,
  },
  refund: Object,
})

const emit = defineEmits(["close", "saved"])
const lookup = useLookupStore()

const {
  errorText,
  handleRequest,
} = useFormErrors()

const formData = ref(
    props.refund
        ? { ...props.refund }
        : {
          id: null,
          return_id: props.return_id,
          status_id: lookup.initialRefundStatus?.id ?? null,
          amount: "",
          currency: "EUR",
          reference: "",
          processed_at: "",
        }
)

const statusOptions = computed(() => lookup.refundStatuses.map(i => ({label: i.name, value: i.id})))
const currentStatus = computed(() => lookup.refundStatus(formData.value.status_id))

const saveRefund = () => handleRequest(async () => {
  await api.post(`/api/refunds`, formData.value)
  emit("saved")
})


</script>

<template>
  <div class="return-refund-form">
    <div class="form-full">
      <h4 class="text-muted">Neue Erstattung</h4>
      <div class="fields">
        <div class="first-row flex gap-24">
          <div class="currency">
            <FormGroup name="amount" label="Betrag ">
              <FormFieldText name="amount" v-model="formData.amount"/>
            </FormGroup>
            <FormGroup name="currency" label="Währung">
              <FormFieldCurrency name="currency" v-model="formData.currency"/>
            </FormGroup>
          </div>
          <FormGroup name="reference" label="Referenz">
            <FormFieldText name="reference" v-model="formData.reference" placeholder="z.B. PayPal-ID, Gutschrift oder Bankreferenz"/>
          </FormGroup>
        </div>
        <div class="second-row flex gap-24">
          <FormGroup name="status_id" label="Status">
            <FormFieldSelect name="status_id" v-model="formData.status_id" :options="statusOptions" />
          </FormGroup>
          <FormGroup name="processed_at" label="Verarbeitet am">
            <FormFieldDateTime
                name="processed_at"
                v-model="formData.processed_at"
                :disabled="currentStatus?.code !== 'refunded'"
                placeholder="Datum und Uhrzeit auswählen"
            />
          </FormGroup>

        </div>
      </div>
      <div class="flex flex-col gap-12 items-end">
      <div class="text-right text-danger" v-if="errorText">{{ errorText }}</div>
        <div class="form__actions flex gap-10 justify-end">
          <button type="button" class="btn btn-outline-primary btn-sm" @click="$emit('close')">
            <X/>
            Abbrechen
          </button>
          <button type="button" class="btn btn-primary btn-sm" @click="saveRefund">
            <Save/>
            Speichern
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";
.return-refund-form {
  padding: 24px 12px;
  .fields{
    border-top: 1px solid variables.$border-color;
    padding: 24px 0;
  }
  .first-row {
    margin-bottom: 16px;

    > * {
      flex: 1;
    }

    .currency {
      flex: 1;
      display: flex;
      gap: 12px;
    }
  }

  .second-row {
    > * {
      flex: 1;
    }
  }
}
</style>
