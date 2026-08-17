<script setup>
import {ref} from "vue";
import {Save, X} from "lucide-vue-next";
import {api} from "@/api/api.js";
import {useLookupStore} from "@/stores/lookups.js";
import FormGroup from "@/components/forms/FormGroup.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import {useFormErrors} from "@/utils/useFormErrors.js";
import FormFieldCurrency from "@/components/forms/FormFieldCurrency.vue";

const props = defineProps({
  return_id: {
    type: Number,
    required: true,
  }
})

const emit = defineEmits(["close", "saved"])
const lookup = useLookupStore()


const formData = ref(
{
        id: null,
        return_id: props.return_id,
        direction: 1,
        carrier: "",
        tracking_number: "",
        payer: 2,
        amount: "",
        currency: 'EUR',
      }
)

const {
  errorText,
  handleRequest,
} = useFormErrors()

const saveShipment = () => handleRequest(async () => {
  await api.post(`/api/shipments`, formData.value)
  emit("saved")
})
</script>

<template>
  <div class="return-shipment-form">
    <div class="form-full">
      <h4 class="text-muted">Neue Sendung</h4>
      <div class="fields">
        <div class="first-row flex gap-24">
          <FormGroup name="direction" label="Richtung">
            <FormFieldSelect v-model="formData.direction" :options="lookup.shipmentDirectionOptions" />
          </FormGroup>
          <FormGroup name="payer" label="Zahler">
            <FormFieldSelect
                name="payer"
                v-model="formData.payer"
                :options="lookup.shipmentPayerOptions"
                placeholder="Zahler auswählen"
            />
          </FormGroup>

          <div class="currency">
            <FormGroup name="amount" label="Kosten">
              <FormFieldText name="amount" v-model="formData.amount"/>
            </FormGroup>
            <FormGroup name="currency" label="Währung">
              <FormFieldCurrency name="currency" v-model="formData.currency"/>
            </FormGroup>
          </div>
        </div>
        <div class="second-row flex gap-24">
          <FormGroup name="carrier" label="Dienstleister">
            <FormFieldSelect
                v-model="formData.carrier"
                :options="lookup.shipmentCarrierOptions"
                placeholder="Dienstleister auswählen"
            />
          </FormGroup>

          <FormGroup name="tracking_number" label="Trackingnummer">
            <FormFieldText name="tracking_number" v-model="formData.tracking_number" />
          </FormGroup>
        </div>


      </div>

      <div class="flex flex-col gap-12 items-end">
        <div class="text-right text-danger" v-if="errorText">{{ errorText }}</div>
        <div class=" flex gap-12 justify-end">

          <button type="button" class="btn btn-outline-primary btn-sm" @click="$emit('close')">
            <X /> Abbrechen
          </button>
          <button type="button" class="btn btn-primary btn-sm" @click="saveShipment">
            <Save /> Speichern
          </button>
        </div>
      </div>
    </div>

  </div>


</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.return-shipment-form {
  padding: 24px 12px;
  .form-full{

    .fields{
      border-top: 1px solid variables.$border-color;
      padding: 24px 0;
    }

    .first-row{
      margin-bottom: 16px;
      >*{
        flex: 1;
      }
      .currency {
        flex: 1;
        display: flex;
        gap: 12px;
      }

    }
    .second-row{
      >*{
        flex: 1;
      }
      .group-tracking_number{
        flex: 2;
      }
    }
  }
}
</style>
