<script setup>
import ShipmentItem from "./ShipmentItem.vue";
import ReturnShipmentForm from "@/pages/app/return/shipment/ReturnShipmentForm.vue";
import {ref} from "vue";
import { Plus } from "lucide-vue-next";

const props = defineProps({
  return_id: {
    type: Number,
    required: true,
  },
  items: {
    type: Array,
    default: () => [],
  },
  editable: {
    type: Boolean,
    default: true,
  },
})

defineEmits(["updated"])

const shippingForm = ref(false)


</script>

<template>
  <div class="shipment-list">
    <table class="shipment-table table" v-if="props.items.length">
      <thead>
      <tr>

        <th>Richtung</th>
        <th>Payer</th>
        <th>Status</th>
        <th>Carrier</th>
        <th>Tracking</th>
        <th>Erstellt</th>
        <th>ID</th>
      </tr>
      </thead>

      <tbody>
      <ShipmentItem
        v-for="item in props.items"
        :key="item.id"
        :item="item"
        @updated="$emit('updated')"
      />
      </tbody>
    </table>

    <div class="add-shipment" v-if="editable">
      <ReturnShipmentForm
          v-if="shippingForm"
          :return_id="return_id"
          @saved="$emit('updated')"
          @close="shippingForm = false"
      />
      <div v-else class="add-button text-right">
        <button class="btn btn-primary btn-sm" @click="shippingForm = true"><Plus/> Versand anlegen</button>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.shipment-list {
  .add-shipment {
    margin: 12px;
  }
}

</style>

