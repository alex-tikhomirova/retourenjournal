<script setup>

import Modal from "@/components/Modal.vue";
import {Check} from "lucide-vue-next";

defineProps({
  suggestedCustomers: Array,
})

defineEmits(["useCustomer", "close"])
</script>

<template>
  <Modal title="Ähnliche Kunden gefunden"  v-if="suggestedCustomers.length" @close="$emit('close')">
    <div class="suggesed-custmoers-modal-body">
      <p class="text-muted text-small">
      Es wurden Kunden mit ähnlichen Kontaktdaten gefunden. Wählen Sie einen bestehenden Kunden aus oder fahren Sie mit
      den eingegebenen Daten fort.
    </p>
      <table class="table">
        <tbody>
        <tr v-for="customer in suggestedCustomers">
          <td>
            <div class="font-bold">{{ customer.name }}</div>
            <div>{{ customer.email }}</div>
          </td>
          <td class="text-center">
            <div>{{ customer.phone }}</div>
          </td>
          <td class="text-right">
            <button class="btn btn-primary" @click="() => $emit('useCustomer', customer)">
              <Check/>
              Übernehmen
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
      <template #footer>
        <div class="text-center">
          <button class="btn btn-outline-primary" @click="$emit('close')">
            Mit neuen Daten fortfahren
          </button>
        </div>
      </template>

  </Modal>
</template>

<style scoped lang="scss">
  .suggesed-custmoers-modal-body{
    >.table{
      margin: 18px 0;
    }
  }
</style>