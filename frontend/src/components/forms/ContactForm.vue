<script setup>
import {ref, watch} from 'vue'
import {api} from '@/api/api.js'
import FormFieldSelect from '@/components/forms/FormFieldSelect.vue'
import FormFieldText from '@/components/forms/FormFieldText.vue'
import FormFieldTextArea from '@/components/forms/FormFieldTextArea.vue'
import FormGroup from '@/components/forms/FormGroup.vue'
import {useFormErrors} from '@/utils/useFormErrors.js'

const props = defineProps({
  topic: {type: String, default: ''},
  subject: {type: String, default: ''},
})

const topicOptions = [
  {value: 'general', label: 'Allgemeine Frage'},
  {value: 'adjustment', label: 'Anpassung anfragen'},
  {value: 'usage', label: 'Frage zur Nutzung'},
  {value: 'technical', label: 'Technisches Problem'},
  {value: 'privacy', label: 'Datenschutz'},
  {value: 'other', label: 'Sonstiges'},
]

const form = ref({
  name: '',
  email: '',
  topic: '',
  subject: '',
  message: '',
})
const isSubmitting = ref(false)
const isSent = ref(false)

const {
  getError,
  hasError,
  clearError,
  clearErrors,
  setErrors,
  handleRequest,
} = useFormErrors()

const normalizeTopic = (topic) => (
  topicOptions.some((option) => option.value === topic) ? topic : ''
)

watch(
  () => props.topic,
  (topic) => {
    form.value.topic = normalizeTopic(topic)
    clearError('topic')
  },
  {immediate: true},
)

watch(
  () => props.subject,
  (subject) => {
    form.value.subject = subject
    clearError('subject')
  },
  {immediate: true},
)

const onFieldUpdate = (name) => {
  clearError(name)
  isSent.value = false
}

const onSubmit = async () => {
  clearErrors()
  isSent.value = false

  const requiredErrors = {}
  for (const field of ['name', 'email', 'topic', 'message']) {
    if (!form.value[field]) {
      requiredErrors[field] = ['Dieses Feld ist erforderlich.']
    }
  }

  if (Object.keys(requiredErrors).length) {
    setErrors(requiredErrors)
    return
  }

  isSubmitting.value = true
  const response = await handleRequest(() => api.post('/api/contact', form.value))
  isSubmitting.value = false

  if (!response) {
    return
  }

  form.value.subject = ''
  form.value.message = ''
  isSent.value = true
}
</script>

<template>
  <form class="contact-form grid gap-20" @submit.prevent="onSubmit">
    <div class="contact-fields grid gap-20">
      <FormGroup name="name" label="Name" :error="getError('name')" required>
        <FormFieldText
            v-model.trim="form.name"
            name="name"
            autocomplete="name"
            :invalid="hasError('name')"
            @update:modelValue="onFieldUpdate('name')"
        />
      </FormGroup>

      <FormGroup name="email" label="E-Mail für die Antwort" :error="getError('email')" required>
        <FormFieldText
            v-model.trim="form.email"
            name="email"
            type="email"
            autocomplete="email"
            inputmode="email"
            :invalid="hasError('email')"
            @update:modelValue="onFieldUpdate('email')"
        />
      </FormGroup>

      <FormGroup name="topic" label="Thema" :error="getError('topic')" required>
        <FormFieldSelect
            v-model="form.topic"
            name="topic"
            :options="topicOptions"
            placeholder="Thema auswählen"
            :invalid="hasError('topic')"
            @update:modelValue="onFieldUpdate('topic')"
        />
      </FormGroup>

      <FormGroup name="subject" label="Betreff" :error="getError('subject')">
        <FormFieldText
            v-model.trim="form.subject"
            name="subject"
            placeholder="Optionaler Betreff"
            :invalid="hasError('subject')"
            @update:modelValue="onFieldUpdate('subject')"
        />
      </FormGroup>

      <FormGroup
          class="contact-field-wide"
          name="message"
          label="Nachricht"
          :error="getError('message')"
          required
      >
        <FormFieldTextArea
            v-model.trim="form.message"
            name="message"
            rows="7"
            :invalid="hasError('message')"
            @update:modelValue="onFieldUpdate('message')"
        />
      </FormGroup>
    </div>

    <p>
      Ihre Angaben werden zur Bearbeitung Ihrer Anfrage verarbeitet. Weitere Informationen finden Sie in der
      <RouterLink to="/legal/privacy">Datenschutzerklärung</RouterLink>.
    </p>

    <p v-if="getError('_general')" class="text-danger">{{ getError('_general') }}</p>
    <p v-if="isSent" class="text-success" role="status">Ihre Nachricht wurde gesendet.</p>

    <div class="contact-form-actions flex justify-end">
      <button class="btn btn-primary" type="submit" :disabled="isSubmitting">
        {{ isSubmitting ? 'Nachricht wird gesendet...' : 'Nachricht senden' }}
      </button>
    </div>
  </form>
</template>
