<script setup>

import {computed} from "vue";
import {ChevronDown, ChevronsUpDown, ChevronUp} from "lucide-vue-next";

const props = defineProps({
  field: {
    type: String,
    required: true,
  },
})
const active = defineModel({
  type: String,
  default: '',
})

const isDesc = computed(() => active.value.indexOf('-') === 0)
const isActive = computed(() => active.value.replace(/^-/, '') === props.field)
const nextSort = computed(() => isActive.value ?
    (isDesc.value ? '' : '-') + props.field :
    props.field)

</script>

<template>
  <a href="#" class="sort-link" @click.prevent="active = nextSort">
    <ChevronUp v-if="isActive && !isDesc" />
    <ChevronDown v-if="isActive && isDesc"/>
    <ChevronsUpDown v-if="!isActive" />
    <slot/>
  </a>
</template>

<style scoped lang="scss">
.sort-link{
  display: flex;
  align-items: center;
  svg{
    height: 0.9rem;
  }
}

</style>