<script setup>

import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
import {ref} from "vue";
import {useOrgStore} from "@/stores/org.js";
import {useRouter} from "vue-router";

const org = useOrgStore()
const router = useRouter()

const errorMessage = ref(null)
const formData = ref({
  name: '',
})

const onSubmit = async () => {
  const res = await org.createOrganization(formData.value)
  if (!res.ok) {
    errorMessage.value =
        res.error?.response?.data?.message

        ?? 'Fehler beim Erstellen'
    return
  }

  router.push('/app')

}
</script>

<template>
  <div class="create-organization-page">
    <h1>Neue Organisation</h1>
    <br/>
    <div class="create-organization-form">
      <FormGroup name="name" label="Name">
        <FormFieldText v-model="formData.name" name="name"/>
      </FormGroup>
      <button class="btn btn-primary" type="submit" :disabled="org.isLoading" @click="onSubmit">
        {{ org.isLoading ? 'Wird erstellt…' : 'Organisation erstellen' }}
      </button>

      <p v-if="errorMessage" class="text-danger">{{ errorMessage }}</p>
    </div>

  </div>
</template>

<style scoped>
 .create-organization-page{
   max-width: 420px;
   margin: 40px auto;
   padding: 16px;
 }
 .create-organization-form{
   display: grid;
   gap: 16px;
 }
</style>