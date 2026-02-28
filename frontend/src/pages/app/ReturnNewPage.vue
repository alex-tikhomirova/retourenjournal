<script setup>

import PageCard from "@/components/PageCard.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import {computed, ref} from "vue";
import {api} from "@/api/api.js";
import {useRouter} from "vue-router";
import {Plus, Minus} from "lucide-vue-next";
import ToolBar from "@/components/ToolBar.vue";

const router = useRouter()

const formData = ref({
  return_number: '',
  order_reference: '',
  reason: '',
  customer: {
    id: '',
    name: '',
    email: '',
    phone: '',
    address_text: '',
  },
  items: [],
})

const lastLine = computed(() => formData.value.items.reduce(
    (last, current) => Math.max(last, Number(current.line_no)),
    0)
)
const addItem = () => {
  formData.value.items.push({
    line_no: lastLine.value + 1,
    sku: '',
    item_name: '',
    quantity: '1',
    price: '',
    currency: 'EUR',
  })
}
const removeItem = (idx) => {
  formData.value.items.splice(idx,1)
}
addItem()

const save = async () => {
  const {data} = await api.post('/api/returns/store', formData.value);
  if (data?.data?.id ?? null){
    router.push('/app/returns')
  }
}


</script>

<template>

  <ToolBar :on-back="() => router.push('/app/returns')" title="Neue Retoure erstellen" subtitle="Erfassen Sie die Retourendaten">
    <template #right>
      <button class="btn btn-outline-primary" @click="router.push('/app/returns')">Abbrechen</button>
      <button class="btn btn-primary" @click="save">Speichern</button>
    </template>
  </ToolBar>


  <div class="return-form-page">
    <PageCard class="customer" title="Kunde">
      <FormGroup name="customer.name" label="Name">
        <FormFieldText v-model="formData.customer.name" name="customer.name"/>
      </FormGroup>
      <FormGroup name="customer.email" label="E-mail">
        <FormFieldText v-model="formData.customer.email" name="customer.email"/>
      </FormGroup>
      <FormGroup name="customer.phone" label="Telefonnummer">
        <FormFieldText v-model="formData.customer.phone" name="customer.phone"/>
      </FormGroup>
      <FormGroup name="customer.address_text" label="Adresse">
        <FormFieldText v-model="formData.customer.address_text" name="customer.address_text"/>
      </FormGroup>
    </PageCard>
    <PageCard class="return" title="Grundinformationen">
      <FormGroup name="return_number" label="Retourennummer">
        <FormFieldText v-model="formData.return_number" name="return_number"/>
      </FormGroup>
      <FormGroup name="order_reference" label="Bestellnummer">
        <FormFieldText v-model="formData.order_reference" name="order_reference"/>
      </FormGroup>
      <FormGroup name="reason" label="reason">
        <FormFieldText v-model="formData.reason" name="reason"/>
      </FormGroup>
    </PageCard>
    <PageCard class="items" title="Artikel">
      <table class="table grid-table">
        <thead>
        <tr>
          <th class="pos">Pos.</th>
          <th class="sku">SKU</th>
          <th class="name">Artikel</th>
          <th class="qty">Menge</th>
          <th class="price">Preis</th>
          <th class="currency">Währung</th>
          <th class=""> </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, idx) in formData.items">
          <td>
            <FormFieldText v-model="item.line_no" type="number" :name="`line_no-${idx}`"/>
          </td>
          <td>
            <FormFieldText v-model="item.sku" :name="`sku-${idx}`"/>
          </td>
          <td>
            <FormFieldText v-model="item.item_name" :name="`item_name-${idx}`"/>
          </td>
          <td>
            <FormFieldText v-model="item.quantity" type="number" :name="`quantity-${idx}`"/>
          </td>
          <td>
            <FormFieldText v-model="item.price" :name="`price-${idx}`"/>
          </td>
          <td>
            <FormFieldText v-model="item.currency" :name="`currency-${idx}`" disabled/>
          </td>
          <td>
            <button class="btn  btn-outline-primary" @click="() => removeItem(idx)"><Minus/></button>
          </td>
        </tr>
        </tbody>
      </table>

      <button class="btn btn-primary" @click="addItem"><Plus  /></button>
    </PageCard>

  </div>
</template>

<style lang="scss">
.return-form-page {
  display: flex;
  gap: 30px;

  .customer {

  }

  .return {
    flex: 1;
  }

  .items {
    .pos, .qty, .currency {
      width: 90px;
    }
    .name{
      width: 400px;
    }
  }
}
</style>