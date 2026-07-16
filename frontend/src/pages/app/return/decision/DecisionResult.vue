<script setup>
import {Truck, BanknoteArrowUp, Check} from "lucide-vue-next";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import {ref} from "vue";
import {useCurrencyStore} from "@/stores/currency.js";
import DecisionBadges from "@/pages/app/return/decision/DecisionBadges.vue";
import CheckBox from "@/components/forms/CheckBox.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import {useLookupStore} from "@/stores/lookups.js";
import FormGroup from "@/components/forms/FormGroup.vue";
import {api} from "@/api/api.js";
import DecisionNextStatus from "./DecisionNextStatus.vue";

const props = defineProps({
  decision: Object,
  returnData: Object,
})

const emit = defineEmits(['reset', 'confirm'])

const currency = useCurrencyStore()
const refundValue = ref(props.returnData.items.reduce((total, i) => total + currency.toActive(i.unit_price_cents, i.currency), 0) / 100)

const lookup = useLookupStore()

const formData = ref({
  refund: {
    auto: true,
    amount: refundValue,
  },
  shipment: {
    auto: true,
    direction: 1,
    carrier: 'DHL',
    payer: 2,
    amount: '0',
  }
})

const saving = ref(false)
const save =  async () => {
  saving.value = true
  try {
    const {data} = await api.patch(`/api/returns/${props.returnData.id}/decision`, {decision_id: props.decision.id})
    if (data.data?.decision_id === props.decision.id){
      if (props.decision.requires_refund && formData.value.refund.auto){
        await api.post(`/api/returns/${props.returnData.id}/refunds`, formData.value.refund)
      }
      if (props.decision.requires_outbound_shipment && formData.value.shipment.auto){
        await api.post(`/api/returns/${props.returnData.id}/shipments`, formData.value.shipment)
      }
      emit('confirm')
    }

  }catch (error) {

  }

}

</script>

<template>


  <div class="decision-result flex flex-col gap-12 items-stretch" :class="{danger: decision.outcome === 'reject', primary: decision.outcome === 'approve'}">
    <div class=" flex justify-between"><span class="title">Ausgewählte Entscheidung</span> <a href="#" @click.prevent="$emit('reset')" class="text-small">Zurücksetzen</a></div>

    <div class="selected-decision ">
      <span class="text-muted text-small">Du hast gewählt:</span>
      <div class="flex flex-col gap-6 items-start">
        <div class="name">{{ decision.name }}</div>
        <DecisionBadges :decision="decision"/>
      </div>
    </div>

    <DecisionNextStatus :status="decision.nextStatus"/>

    <div class="refund color-card danger" v-if="decision.requires_refund">
      <h4 class="e-title text-muted flex gap-6 font-bold">
        <CheckBox v-model="formData.refund.auto"/> <BanknoteArrowUp /> Rückerstattung
      </h4>
      <div class="e-content">
        <div class="refund-form">
          <FormFieldText name="refund_amount" v-model="formData.refund.amount"/>
          <div class="refund-descr text-muted text-small">Basierend auf {{ props.returnData.items.length }} Artikeln</div>
        </div>
        <div class="color-card compact danger-danger text-danger text-small">
          Der Kunde erhält {{ refundValue.toFixed(2) }} {{ currency.activeCurrency.symbol }} zurückerstattet.
        </div>
      </div>
    </div>


    <div class="return-shipment color-card success" v-if="decision.requires_outbound_shipment">
      <label class="e-title text-muted flex gap-6 font-bold ">
        <CheckBox v-model="formData.shipment.auto" /> <Truck /> Rücksendung erstellen
      </label>
      <div class="e-content">

        <div class="return-form">
          <FormFieldSelect
              v-model="formData.shipment.carrier"
              :options="lookup.shipmentCarrierOptions"
              placeholder="Versanddienstleister auswählen"
          />
          <FormFieldSelect
              name="payer"
              v-model="formData.shipment.payer"
              :options="lookup.shipmentPayerOptions"
              placeholder="Zahlungspflichtigen auswählen"
          />
        </div>
        <div class="return-form">
          <FormGroup name="cost" label="Versandkosten" class="horizontal">
            <FormFieldText name="shipment_amount" v-model="formData.shipment.amount"/>
          </FormGroup>

        </div>
        <div class="color-card success-success compact text-success text-small">
          Versandlabel wird nach Bestätigung erstellt.
        </div>

      </div>
    </div>

    <div class="buttons flex gap-24 ">

      <button class="btn btn-primary btn-block " @click="save">
        <Check/>
        Entscheidung bestätigen
      </button>

    </div>

  </div>




</template>

<style scoped lang="scss">
@use "./../../../../assets/scss/colored-cards";
@use "./../../../../assets/scss/variables";

.decision-result{
  border: 1px solid variables.$border-color;
  padding: 12px;
  border-radius: variables.$border-radius;
  &.primary{
    border-color: variables.$color-primary;
    background-color: variables.$bg-color-primary;
  }
  &.danger{
    border-color: variables.$color-danger;
    background-color: variables.$bg-color-danger;
  }

  .title{

    font-weight: 500;
  }

  .selected-decision{

    .name{
      font-weight: bold;
    }
  }
}


.refund-form, .return-form{
  display: flex;
  gap: 12px;
  margin: 6px 0;
}
</style>