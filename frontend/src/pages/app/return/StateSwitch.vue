<script setup>

import {useLookupStore} from "@/stores/lookups.js";
import {computed} from "vue";
import ReturnStatusLabel from "@/components/ui/return/ReturnStatusLabel.vue";

const lookup = useLookupStore()

const props = defineProps({
  returnModel: Object
})

const value = defineModel({
  type: Number
})

const status = computed(() => props.returnModel.status)
const setState = (code) => {
  const newState = lookup.returnStatus(code, 'code')
  if (newState){
    value.value = newState.id
  }
}


</script>

<template>
  <div class="status-btns flex gap-12" v-if="status">
    <ReturnStatusLabel
        v-if="status.code === 'created'"
        mode="button"
        :status="lookup.returnStatus('waiting_item', 'code')"
        @click="() => setState('waiting_item')"
    />
    <ReturnStatusLabel
        v-if="['created', 'waiting_item'].includes(status.code)"
        mode="button"
        :status="lookup.returnStatus('in_review', 'code')"
        @click="() => setState('in_review')"
    >
      Ware eingetroffen
    </ReturnStatusLabel>
    <ReturnStatusLabel
        v-if="['approved', 'rejected'].includes(status.code)"
        mode="button"
        :status="lookup.returnStatus('closed', 'code')"
        @click="() => setState('closed')"
    >
      Abschließen
    </ReturnStatusLabel>

    <template v-if="status.code === 'in_review' && returnModel.decision">
      <ReturnStatusLabel
          v-if="returnModel.decision.outcome === 'approve'"
          mode="button"
          :status="lookup.returnStatus('approved', 'code')"
          @click="() => setState('approved')"
      />
      <ReturnStatusLabel
          v-if="returnModel.decision.outcome === 'reject'"
          mode="button"
          :status="lookup.returnStatus('rejected', 'code')"
          @click="() => setState('rejected')"
      />
    </template>
    <ReturnStatusLabel
        v-if="['closed', 'cancelled'].includes(status.code)"
        mode="button"
        :status="lookup.returnStatus('in_review', 'code')"
        @click="() => setState('in_review')"
    >
      Wiederherstellen
    </ReturnStatusLabel>
  </div>
</template>

<style scoped lang="scss">

</style>
