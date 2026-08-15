<script setup>

import FormFieldSelect from "@/components/forms/FormFieldSelect.vue";

const props = defineProps({
  name: String,
  placeholder: {
    type: String,
    default: "",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  invalid: {
    type: Boolean,
    default: false,
  }

})
const value = defineModel({
  type: [String, Number, Boolean]
})

const options = [
    'EUR'
]
</script>

<template>
  <FormFieldSelect
      class="currency-input"
      v-model="value"
      :name="name"
      :options="['EUR']"
      :disabled="disabled"
      :invalid="invalid"
      :placeholder="placeholder"
  />

</template>

<style scoped lang="scss">
  .currency-input{
    width: 80px;
  }
</style>