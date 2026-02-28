<script setup>

import FormGroup from "@/components/forms/FormGroup.vue";
import {useLookupStore} from "@/stores/lookups.js";
import {useId} from "vue";

const value = defineModel({
  type: Number
})

const lookup = useLookupStore()

const inputId = useId()
</script>

<template>
  <FormGroup name="decision" label="Entscheidung">
    <select
        :id="inputId"
        v-model="value"
        name="input-decision"

        class="input"
        :aria-invalid="false"
        :aria-describedby="`${inputId}-error`"
    >
      <option disabled value="">
        Bitte auswählen
      </option>

      <option
          v-for="option in lookup.returnDecisions"
          :key="option.id"
          :value="option.id"
      >
        {{ option.name }}
      </option>
    </select>

  </FormGroup>
</template>

<style scoped lang="scss">

</style>