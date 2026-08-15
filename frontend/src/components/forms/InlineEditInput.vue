<script setup>
import {computed, nextTick, onBeforeUnmount, onMounted, useTemplateRef} from "vue";
import {Check, X} from "lucide-vue-next";

const props = defineProps({
  size: {
    type: String,
    default: "md",
    validator: (val) => ["xs", "sm", "md", "lg"].includes(val),
  },
  showActions: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["close", "save"]);
const element = useTemplateRef('element')

const closeOutside = (event) => {
  const target = event.target
  if (target instanceof Node && !element.value?.contains(target)) {
    emit('close')
  }
}

onMounted(() => nextTick(() => {
  if (element.value) {
    document.addEventListener('click', closeOutside, true)
  }
}))
onBeforeUnmount(() => document.removeEventListener('click', closeOutside, true))

const buttonSizeClass = computed(() => {
  if (props.size === "lg") return "btn-lg";
  if (props.size === "sm" || props.size === "xs") return "btn-sm";
  return "";
});


</script>

<template>
  <div ref="element" :class="{'input-group': showActions}">
    <slot/>
    <button
      v-if="showActions"
      type="button"
      class="btn btn-outline-primary"
      :class="buttonSizeClass"
      aria-label="Save"
      @click="$emit('save')"
    >
      <Check />
    </button>
    <button
      v-if="showActions"
      type="button"
      class="btn btn-outline-primary"
      :class="buttonSizeClass"
      aria-label="Close"
      @click="$emit('close')"
    >
      <X />
    </button>

  </div>
</template>

