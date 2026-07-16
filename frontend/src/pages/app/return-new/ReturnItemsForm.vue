<script setup>
import {computed} from "vue";
import {Plus, Trash2} from "lucide-vue-next";
import PageCard from "@/components/PageCard.vue";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";

defineProps({
  getError: Function,
  hasError: Function,
  clearError: Function,
})

const items = defineModel({
  type: Array,
  default: () => [],
})

const lastLine = computed(() => items.value.reduce(
    (last, current) => Math.max(last, Number(current.line_no)),
    0)
)

const addItem = () => {
  items.value.push({
    line_no: lastLine.value + 1,
    sku: '',
    serial: '',
    item_name: '',
    quantity: '1',
    unit_price: null,
    currency: 'EUR',
  })
}

const removeItem = (idx) => {
  items.value.splice(idx, 1)
  items.value.forEach((item, itemIdx) => {
    item.line_no = itemIdx + 1
  })
}

if (!items.value.length) {
  addItem()
}
</script>

<template>
  <PageCard class="return-form-items" title="Artikel">
    <template #title>
      <span class="text-small text-muted">
        {{ items.length }} {{ items.length < 2 ? `Position` : `Positionen` }}
      </span>
    </template>
    <table class="table">
      <thead>
      <tr>
        <th class="pos">Pos.</th>
        <th class="sku">SKU</th>
        <th class="name">Artikel <span class="text-danger">*</span></th>
        <th class="serial">Produkt-/Seriennummer</th>
        <th class="qty">Menge <span class="text-danger">*</span></th>
        <th class="price">Preis</th>
        <th class="currency">Währung</th>
        <th class=""> </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(item, idx) in items">
        <td class="text-center">
          {{ item.line_no }}
        </td>
        <td class="sku">
          <FormFieldText v-model="item.sku" :name="`items.${idx}.sku`" placeholder="z.B. LBL9898"/>
        </td>
        <td>
          <FormFieldText
              v-model="item.item_name"
              :name="`items.${idx}.item_name`"
              placeholder="Artikelbezeichnung"
              :invalid="hasError(`items.${idx}.item_name`)"
              @update:modelValue="() => clearError(`items.${idx}.item_name`)"
          />
          <p
              v-if="hasError(`items.${idx}.item_name`)"
              class="input-error"
          >
            {{ getError(`items.${idx}.item_name`) }}
          </p>
        </td>
        <td class="serial">
          <FormFieldText v-model="item.serial" :name="`items.${idx}.serial`" placeholder="z.B. SN1234567890"/>
        </td>
        <td class="qty">
          <FormFieldText
              v-model="item.quantity"
              type="number"
              :name="`items.${idx}.quantity`"
              :invalid="hasError(`items.${idx}.quantity`)"
              @update:modelValue="() => clearError(`items.${idx}.quantity`)"
          />
          <p
              v-if="hasError(`items.${idx}.quantity`)"
              class="input-error"
          >
            {{ getError(`items.${idx}.quantity`) }}
          </p>
        </td>
        <td class="price">
          <FormFieldText
              v-model="item.unit_price"
              :name="`items.${idx}.unit_price`"
              :invalid="hasError(`items.${idx}.unit_price`)"
              @update:modelValue="() => clearError(`items.${idx}.unit_price`)"
          />
          <p
              v-if="hasError(`items.${idx}.unit_price`)"
              class="input-error"
          >
            {{ getError(`items.${idx}.unit_price`) }}
          </p>
        </td>
        <td class="qty">
          <FormFieldSelect v-model="item.currency" :name="`items.${idx}.currency`" :options="['EUR']"/>
        </td>
        <td class="text-center">
          <button class="btn btn-link" @click="() => removeItem(idx)"><Trash2 /></button>
        </td>
      </tr>
      </tbody>
    </table>

    <div class="footer">
      <button class="btn btn-outline-primary btn-sm" @click="addItem">
        <Plus/>
        Position hinzufügen
      </button>
    </div>
  </PageCard>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.return-form-items {
  flex: 1 1 100%;
  .qty {
    width: 100px;
  }

  .price {
    width: 150px;
  }

  .footer {
    padding: variables.$module-padding;
  }
}
</style>
