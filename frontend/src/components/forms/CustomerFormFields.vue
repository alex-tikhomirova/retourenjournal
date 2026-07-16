<script setup>
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormFieldTextArea from "@/components/forms/FormFieldTextArea.vue";
import FormGroup from "@/components/forms/FormGroup.vue";

const customer = defineModel({
  type: Object,
  default: () => ({
    id: '',
    name: '',
    email: '',
    phone: '',
    address_text: '',
  }),
})

const emit = defineEmits(['change'])

const {
  getError,
  hasError,
  clearError,
} = defineProps({
  getError: Function,
  hasError: Function,
  clearError: Function,
})

const onNameChange = () => {
  clearError('customer.name')
  emit('change', 'name')
}

</script>

<template>
  <div class="customer-form-fields">
    <FormGroup name="customer.name" label="Name" class="flex-1" :error="getError('customer.name')" required>
      <FormFieldText
          v-model="customer.name"
          name="customer.name"
          :invalid="hasError('customer.name')"
          @update:modelValue="onNameChange"
      />
    </FormGroup>
    <FormGroup name="customer.email" label="E-mail" class="flex-1">
      <FormFieldText v-model="customer.email" name="customer.email" @update:modelValue="$emit('change', 'email')"/>
    </FormGroup>
    <FormGroup name="customer.phone" label="Telefonnummer" class="flex-1">
      <FormFieldText v-model="customer.phone" name="customer.phone" @update:modelValue="$emit('change', 'phone')"/>
    </FormGroup>
    <FormGroup name="customer.address_text" label="Adresse" class="flex-1">
      <FormFieldTextArea rows="2" v-model="customer.address_text" name="customer.address_text" @update:modelValue="$emit('change', 'address_text')"/>
    </FormGroup>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";
.customer-form-fields {
  display: flex;
  flex-wrap: wrap;
  gap: variables.$module-gap;

  > * {
    flex: 1 1 calc((100% - #{variables.$module-gap}) / 2);
  }
}
</style>