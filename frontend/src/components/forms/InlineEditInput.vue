<script setup>
import {computed, ref, watch} from "vue";
import {Check, X} from "lucide-vue-next";
import FormFieldText from "@/components/forms/FormFieldText.vue";

const props = defineProps({
  value: {
    type: [String, Number],
    default: "",
  },
  type: {
    type: String,
    default: "text",
    validator: (val) => ["text", "number"].includes(val),
  },
  size: {
    type: String,
    default: "md",
    validator: (val) => ["xs", "sm", "md", "lg"].includes(val),
  },
  name: {
    type: String,
    default: "inline-edit",
  },
});

const emit = defineEmits(["close", "save"]);

const localValue = ref(props.value);

watch(
  () => props.value,
  (nextValue) => {
    localValue.value = nextValue;
  }
);

const inputSizeClass = computed(() => `input-${props.size}`);
const buttonSizeClass = computed(() => {
  if (props.size === "lg") return "btn-lg";
  if (props.size === "sm" || props.size === "xs") return "btn-sm";
  return "";
});

const normalizeValue = () => {
  if (props.type !== "number") {
    return localValue.value;
  }

  if (localValue.value === "" || localValue.value === null) {
    return "";
  }

  const asNumber = Number(localValue.value);
  return Number.isNaN(asNumber) ? localValue.value : asNumber;
};

const save = () => emit("save", normalizeValue());
const close = () => emit("close");
</script>

<template>
  <div class="input-group">
    <FormFieldText
      v-model="localValue"
      :name="name"
      :type="type"
      :class="inputSizeClass"
    />

    <button
      type="button"
      class="btn btn-outline-primary"
      :class="buttonSizeClass"
      aria-label="Save"
      @click="save"
    >
      <Check />
    </button>
    <button
      type="button"
      class="btn btn-outline-primary"
      :class="buttonSizeClass"
      aria-label="Close"
      @click="close"
    >
      <X />
    </button>

  </div>
</template>

