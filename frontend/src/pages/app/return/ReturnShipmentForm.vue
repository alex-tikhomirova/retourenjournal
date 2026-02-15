<script setup>

import FormGroup from "@/components/forms/FormGroup.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import {ref} from "vue";
import {Check, X} from "lucide-vue-next";
import {api} from "@/api/api.js";

const props = defineProps({
  return_id: {
    type: Number,
    required: true,
  }
})

const emit = defineEmits(['close', 'saved'])

const carrierOptions = ['DHL', 'DPD', 'Hermes', 'UPS', 'Other'].map((c) => {
  return {value: c, label: c}
})

const directionOptions = [
  { value: 1, label: 'Rücksendung vom Kunden' },
  { value: 2, label: 'Versand an den Kunden' },
]

const payerOptions = [
  { value: 1, label: 'Kunde' },
  { value: 2, label: 'Händler' },
  { value: 3, label: 'Plattform / Marktplatz' },
  { value: 4, label: 'Geteilt (anteilig)' },
  { value: 5, label: 'Unbekannt' },
]

const formData = ref({
  direction: 1,
  carrier: '',
  tracking_number: '',
  payer: 2,
  cost: '',
})

const save = () => {
  api.post(`/api/returns/${props.return_id}/shipments`, formData.value).then(() => {
    emit('saved')
  })

}
</script>

<template>
  <FormGroup name="direction" label="Richtung">
    <FormFieldSelect v-model="formData.direction" :options="directionOptions"/>
  </FormGroup>
  <FormGroup name="carrier" label="Versanddienstleister">
    <FormFieldSelect v-model="formData.carrier" :options="carrierOptions" placeholder="Versanddienstleister auswählen">
    </FormFieldSelect>
  </FormGroup>
  <FormGroup name="tracking_number" label="Tracking-Nummer">
    <FormFieldText name="tracking_number" v-model="formData.tracking_number"/>
  </FormGroup>
  <FormGroup name="payer" label="Zahlungspflichtiger">
    <FormFieldSelect name="payer" v-model="formData.payer" :options="payerOptions"/>
  </FormGroup>
  <FormGroup name="cost" label="Versandkosten">
    <FormFieldText name="cost" v-model="formData.cost"/>
  </FormGroup>
  <button class="btn btn-outline-primary" @click="$emit('close')"><X /> Abbrechen</button>
  <button class="btn btn-primary" @click="save"><Check/> Speichern</button>
</template>

<style scoped lang="scss">

</style>