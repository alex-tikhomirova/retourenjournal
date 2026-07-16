<script setup>
import {ref} from "vue";
import RefundItem from "@/pages/app/return/refund/RefundItem.vue";
import RefundForm from "@/pages/app/return/refund/RefundForm.vue";
import {Plus} from "lucide-vue-next";

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
  }
})

defineEmits(["updated"])

const showForm = ref(false)
</script>

<template>
  <div class="refund-list">
    <table class="refund-table table" v-if="props.items.length">
      <thead>
      <tr>
        <th>Referenz</th>
        <th>Betrag</th>
        <th>Status</th>
        <th>Erstellt</th>
        <th>Verarbeitet</th>
      </tr>
      </thead>

      <tbody>

      <RefundItem
        v-for="item in props.items"
        :key="item.id"
        :item="item"
        @updated="$emit('updated')"
      />
      </tbody>
    </table>
    <div class="add-refund" v-if="editable">
      <RefundForm
          v-if="showForm"
          :return_id="return_id"
          @saved="$emit('updated')"
          @close="showForm = false"
      />
      <div v-else class="add-button text-right">
        <button class="btn btn-primary btn-sm" @click="showForm = true"><Plus/> Rückerstattung anlegen</button>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .refund-list{
    .add-refund{
      margin: 12px;
    }
  }
</style>