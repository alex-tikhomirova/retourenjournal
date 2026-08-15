<script setup>

import PageCard from "@/components/PageCard.vue";
import {Pencil, Save, X} from "lucide-vue-next";
import {ref} from "vue";
import {api} from "@/api/api.js";
import {useFormErrors} from "@/utils/useFormErrors.js";
import FormFieldText from "@/components/forms/FormFieldText.vue";
import FormGroup from "@/components/forms/FormGroup.vue";
import FormFieldTextArea from "@/components/forms/FormFieldTextArea.vue";

const props = defineProps({
  editable: {
    type: Boolean,
    default: true,
  },
  item: {
    type: Object,
    required: true
  }
})

const formData = ref({ ...props.item })
const emit = defineEmits(['updated'])
const editMode = ref(false)

const {handleRequest, errorText} = useFormErrors()

const save = () => handleRequest(async () => {
  await api.patch(`/api/returns/${props.item.id}`, {
    order_reference: formData.value.order_reference,
    reason: formData.value.reason,
  });
  editMode.value = false
  emit("updated")
})
</script>

<template>



  <PageCard title="Retourdaten" class="block-info" :class="{'padded': editMode}">
    <template #title>
      <a href="#" class="btn btn-sm btn-link" @click.prevent="editMode = !editMode">
        <template v-if="editable && !editMode"><Pencil />Bearbeiten</template>

      </a>
    </template>
    <div class="return-info">
      <div class="return-form flex flex-col gap-24 items-stretch" v-if="editMode">
        <div class="fields flex flex-col items-stretch gap-12">
          <FormGroup name="order_reference" label="Bestellnummer / Referenz" class="flex-1">
            <FormFieldText v-model="formData.order_reference" name="order_reference"/>
          </FormGroup>
          <FormGroup name="reason" label="Rücksendegrund" class="flex-1">
            <FormFieldTextArea rows="2" v-model="formData.reason" name="reason"/>
          </FormGroup>
        </div>
        <div class="controls flex gap-12 justify-end">
          <div class="text-right text-danger" v-if="errorText">{{ errorText }}</div>
          <div class="flex gap-10 justify-end">
            <button class="btn btn-outline-primary btn-sm" @click="editMode = false">
              <X/>
              Abbrechen
            </button>
            <button class="btn btn-primary btn-sm" @click="save">
              <Save/>
              Speichern
            </button>
          </div>
        </div>
      </div>
      <table class="table" v-else>
        <tbody>
        <tr>
          <td>Retourennummer:</td>
          <td>{{ item.return_number }}</td>
        </tr>
        <tr>
          <td>Bestellnummer / Referenz:</td>
          <td>{{ item.order_reference ?? '—' }}</td>
        </tr>
        <tr>
          <td>Rücksendegrund:</td>
          <td>{{ item.reason ?? '—' }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </PageCard>
</template>

<style scoped lang="scss">
  .return-info{
    .return-form{
      .fields{
        width: 100%;
      }
    }
  }
</style>