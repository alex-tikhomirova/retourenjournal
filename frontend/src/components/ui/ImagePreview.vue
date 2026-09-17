<script setup>
import { ref } from "vue";
import { Maximize2 } from "lucide-vue-next";
import Modal from "@/components/Modal.vue";

const props = defineProps({
  src: {
    type: String,
    required: true,
  },
  alt: {
    type: String,
    required: true,
  },
  caption: String,
})

const isOpen = ref(false)
</script>

<template>
  <figure class="image-preview">
    <button class="image-preview-button" type="button" :aria-label="`${alt} vergrößern`" @click="isOpen = true">
      <img :src="src" :alt="alt" loading="lazy" />
      <span class="image-preview-action">
        <Maximize2 :size="16" />
        Vergrößern
      </span>
    </button>
    <figcaption v-if="caption">{{ caption }}</figcaption>
  </figure>

  <Modal v-if="isOpen" :title="caption || alt" size="xl" @close="isOpen = false">
    <img class="image-preview-full" :src="src" :alt="alt" />
  </Modal>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.image-preview {
  width: min(100%, 520px);
  margin: 12px 0 4px;
}

.image-preview-button {
  position: relative;
  display: block;
  width: 100%;
  overflow: hidden;
  border: 1px solid variables.$border-color;
  border-radius: variables.$border-radius;
  background: variables.$head-bg-color;
  cursor: zoom-in;
  padding: 0;
  text-align: left;
}

.image-preview-button img {
  display: block;
  width: 100%;
  max-height: 180px;
  object-fit: contain;
  background: variables.$head-bg-color;
}

.image-preview-action {
  position: absolute;
  right: 12px;
  bottom: 12px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border: 1px solid variables.$border-color;
  border-radius: variables.$border-radius;
  background: rgba(255, 255, 255, 0.92);
  color: variables.$text-color;
  padding: 5px 8px;
  font-size: 0.82rem;
  font-weight: 500;
}

figcaption {
  margin-top: 8px;
  color: variables.$text-color-muted;
  font-size: 0.92rem;
}

.image-preview-full {
  display: block;
  width: 100%;
  height: auto;
}
</style>
