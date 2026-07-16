<script setup>
import {computed, ref} from "vue";
import {Save, X} from "lucide-vue-next";
import {api} from "@/api/api.js";
import {useLookupStore} from "@/stores/lookups.js";
import FormGroup from "@/components/forms/FormGroup.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";

const props = defineProps({
  return_id: {
    type: Number,
    required: true,
  },
  shipment: Object,
})

const emit = defineEmits(["close", "saved"])
const lookup = useLookupStore()



const formData = ref(
  props.shipment
    ? { ...props.shipment }
    : {
        id: null,
        direction: 1,
        carrier: "",
        tracking_number: "",
        payer: 2,
        amount: "",
      }
)

const isNew = computed(() => !props.shipment)
const saveShipment = async () => {
  await api.post(`/api/returns/${props.return_id}/shipments`, formData.value)
  emit("saved")
}
</script>

<template>
  <div class="return-shipment-form">
    <div class="form-full">
      <h4 class="text-muted">Neue Versand</h4>
      <div class="fields">
        <div class="first-row flex gap-24">
          <FormGroup name="direction" label="Richtung">
            <FormFieldSelect v-model="formData.direction" :options="lookup.shipmentDirectionOptions" :disabled="!isNew" />
          </FormGroup>
          <FormGroup name="payer" label="Zahler">
            <FormFieldSelect
                name="payer"
                v-model="formData.payer"
                :options="lookup.shipmentPayerOptions"
                placeholder="Zahler auswaehlen"
            />
          </FormGroup>
          <FormGroup name="amount" label="Preis">
            <FormFieldText name="amount" v-model="formData.amount" />
          </FormGroup>
        </div>
        <div class="second-row flex gap-24">
          <FormGroup name="carrier" label="Carrier">
            <FormFieldSelect
                v-model="formData.carrier"
                :options="lookup.shipmentCarrierOptions"
                placeholder="Carrier auswaehlen"
                :disabled="!isNew"
            />
          </FormGroup>
          <FormGroup name="tracking_number" label="Tracking Nummer">
            <FormFieldText name="tracking_number" v-model="formData.tracking_number" />
          </FormGroup>
        </div>


      </div>
      <div class="form__actions flex gap-10 justify-end">
        <button type="button" class="btn btn-outline-primary btn-sm" @click="$emit('close')">
          <X /> Abbrechen
        </button>
        <button type="button" class="btn btn-primary btn-sm" @click="saveShipment">
          <Save /> Speichern
        </button>
      </div>
    </div>

  </div>


</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.return-shipment-form {
  .form-full{

    .fields{
      border-top: 1px solid variables.$border-color;
      padding: 24px 0;
    }
    .first-row{
      margin-bottom: 16px;
      >*{
        flex: 2;
      }
      .group-cost{
        min-width: 120px;
        flex: 1;
      }
    }
    .second-row{
      .group-carrier{
        flex: 3;
      }
      .track-number{
        flex: 4;
      }
    }
  }
}
</style>
