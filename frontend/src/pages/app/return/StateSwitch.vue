<script setup>

import {useLookupStore} from "@/stores/lookups.js";
import {computed} from "vue";

const lookup = useLookupStore()

const props = defineProps({
  returnModel: Object
})

const value = defineModel({
  type: Number
})

const status = computed(() => props.returnModel.status)
const setState = (code) => {
  const newState = lookup.returnStatuses.find((st) => st.code === code)
  if (newState){
    value.value = newState.id
  }
}

</script>

<template>
  <div class="status-btns flex gap-10" v-if="status">
    <button v-if="status.code === 'created'" class="btn btn-primary" @click="() => setState('waiting_item')">
      Warten auf Rücksendung
    </button>
    <button v-if="['created', 'waiting_item'].includes(status.code)" class="btn btn-primary"
            @click="() => setState('in_review')">
      Ware eingetroffen
    </button>
    <button v-if="['approved', 'rejected'].includes(status.code)" class="btn btn-primary"
            @click="() => setState('closed')">
      Abschließen
    </button>
    <template v-if="status.code === 'in_review' && returnModel.decision">
      <button v-if="returnModel.decision.outcome === 'approve'" class="btn btn-primary" @click="() => setState('approved')">
        Freigeben
      </button>
      <button v-if="returnModel.decision.outcome === 'reject'" class="btn btn-primary" @click="() => setState('rejected')">
        Ablehnen
      </button>
    </template>
  </div>
</template>

<style scoped lang="scss">

</style>