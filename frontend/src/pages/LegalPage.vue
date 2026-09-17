<script setup>
import MarkdownIt from 'markdown-it'
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { legalDocuments, fillLegalTemplate } from '@/content/legal'

const route = useRoute()

const md = new MarkdownIt({
  html: false,
  linkify: true,
  typographer: true,
})

const document = computed(() => legalDocuments[route.params.document])

const rendered = computed(() => {
  if (!document.value) return ''
  return md.render(fillLegalTemplate(document.value.content))
})
</script>

<template>
  <div class="legal-page container">
    <div v-if="document" class="legal-page-content grid gap-24" v-html="rendered"></div>
    <div v-else>Dokument nicht gefunden.</div>
  </div>
</template>
<style scoped lang="scss">
  @use "@/assets/scss/variables";
  .legal-page{
    padding: 40px variables.$module-padding;
  }
</style>