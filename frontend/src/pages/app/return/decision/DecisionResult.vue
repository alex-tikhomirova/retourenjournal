<script setup>
import {CircleCheck, Truck, BanknoteArrowUp, CircleArrowRight, Check, Pencil, Info} from "lucide-vue-next";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import {ref} from "vue";
import {useCurrencyStore} from "@/stores/currency.js";
import DecisionBadges from "@/pages/app/return/decision/DecisionBadges.vue";
import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";
import CheckBox from "@/components/forms/CheckBox.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";
import {useLookupStore} from "@/stores/lookups.js";
import FormGroup from "@/components/forms/FormGroup.vue";
import {api} from "@/api/api.js";

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
    carrier: '',
    payer: 2,
    amount: '0',
  }
})

const saving = ref(false)

const save =  async () => {
  saving.value = true
  if (formData.value.refund.auto){
    //save new refund
  }
  if (formData.value.shipment.auto){
    await api.post(`/api/returns/${props.returnData.id}/shipments`, formData.value.shipment)
  }
  emit('confirm')
}

</script>

<template>


  <div class="decision-result">
    <h3>Ausgewählte Entscheidung</h3>

    <div class="selected-decision entity">
      <h4 class="e-title text-muted">
        <CircleCheck /> Du hast gewählt:
      </h4>
      <div class="e-content">
        <div class="d-name">{{ decision.name }}</div>
        <DecisionBadges :decision="decision"/>
      </div>
    </div>

    <div class="next-status entity" v-if="decision.nextStatus">
      <h4 class="e-title text-muted">
        <CircleArrowRight /> Nächster Status:
      </h4>
      <div class="e-content">
        <ReturnStatusLabel :status="decision.nextStatus"/> -
        <span class="text-muted">{{decision.nextStatus.description}}</span>
      </div>
    </div>

    <div class="refund entity"  v-if="decision.requires_refund">
      <h4 class="e-title text-muted">
        <BanknoteArrowUp /> Rückerstattung
      </h4>
      <div class="e-content">
        <div class="refund-form">
          <CheckBox v-model="formData.refund.auto">
            Rückgeldbetrag erstellen
          </CheckBox>
          <FormFieldText name="refund_amount" v-model="formData.refund.amount"/>
          <div class="refund-descr text-muted text-small">Basierend auf {{ props.returnData.items.length }} Artikeln</div>
        </div>
        <div class="tip">
          <Info size="12" /> Der Kunde erhält {{ refundValue.toFixed(2) }} {{ currency.activeCurrency.symbol }} zurückerstattet.
        </div>
      </div>
    </div>


    <div class="return-shipment entity" v-if="decision.requires_outbound_shipment">
      <h4 class="e-title text-muted">
        <Truck /> Rücksendung
      </h4>
      <div class="e-content">
        <div class="return-form first">
          <CheckBox v-model="formData.shipment.auto">
            Rücksendesendung erstellen
          </CheckBox>
          <FormFieldSelect
              v-model="formData.shipment.carrier"
              :options="lookup.shipmentCarrierOptions"
              placeholder="Versanddienstleister auswählen"
          />
        </div>
        <div class="return-form second">
          <FormGroup name="cost" label="Versandkosten" class="horizontal">
            <FormFieldText name="shipment_amount" v-model="formData.shipment.amount"/>
          </FormGroup>
          <FormFieldSelect
              name="payer"
              v-model="formData.shipment.payer"
              :options="lookup.shipmentPayerOptions"
              placeholder="Zahlungspflichtigen auswählen"
          />
        </div>
        <div class="tip">
          <Info size="12"/> Versandlabel wird nach Bestätigung erstellt.
        </div>
      </div>
    </div>

    <div class="buttons">
      <button class="btn btn-primary btn-block" @click="save">
        <Check/>
        Entscheidung bestätigen
      </button>
      <button class="btn btn-outline-secondary btn-block" @click="$emit('reset')">
        <Pencil/>
        Andere Option wahlen
      </button>
    </div>

  </div>




</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" ;
.entity{
  border: 1px solid variables.$border-color;
  padding: 12px;
  border-radius: variables.$border-radius;
  margin: 12px 0;
  .e-title{
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
  }
  .e-content{

    .tip{
      margin-top: 12px;
    }
  }

  .d-name{
    font-weight: 500;
    font-size: 1rem;
  }

}

.refund-form{
  display: flex;
  gap: 12px;
  .input {
    width: 80px;
  }
}
.return-form{
  display: flex;
  gap: 12px;
  margin: 6px 0;
  &.first >*{
    flex: 1 1 0;
  }
  &.second{
    input[type="text"]{
      width: 80px;
    }
  }
}

.tip{
  background-color: variables.$bg-color-primary;
  color: variables.$color-primary;
  display: flex;
  gap: 6px;
  row-gap: 6px;
  column-gap: 6px;
  padding: 6px 12px;
  border-radius: variables.$border-radius;
}
.buttons{
  .btn{
    margin: 6px 0;
  }
}
</style>