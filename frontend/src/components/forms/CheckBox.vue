<script setup>
const model = defineModel()

defineProps({
  label: {
    type: String,
    default: ''
  },
  disabled: Boolean
})
</script>

<template>
  <label class="ui-checkbox" :class="{ 'is-disabled': disabled }">
    <input
        class="ui-checkbox__input"
        type="checkbox"
        v-model="model"
        :disabled="disabled"
    />

    <span class="ui-checkbox__control"></span>

    <span v-if="$slots.default || label" class="ui-checkbox__label">
      <slot>{{ label }}</slot>
    </span>
  </label>
</template>

<style lang="scss">
@use "./../../assets/scss/variables";

.ui-checkbox {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  user-select: none;

  &__input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;

    &:focus-visible + .ui-checkbox__control {
      box-shadow: 0 0 0 3px rgba(variables.$color-primary, 0.25);
    }

    &:checked + .ui-checkbox__control {
      border-color: variables.$color-primary;
      background: variables.$color-primary;

      &::after {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
      }
    }

    &:disabled + .ui-checkbox__control {
      background: variables.$color-disabled-bg;
      border-color: variables.$color-disabled-border;
    }

    &:disabled ~ .ui-checkbox__label {
      color: variables.$color-disabled-fg;
    }
  }

  &__control {
    position: relative;
    width: 18px;
    height: 18px;
    flex: 0 0 18px;
    border-radius: 5px;
    border: 2px solid variables.$border-color;
    background: variables.$background-color;
    transition: all 0.15s ease;

    &::after {
      content: "";
      position: absolute;
      left: 50%;
      top: 50%;
      width: 10px;
      height: 8px;
      opacity: 0;
      transform: translate(-50%, -50%) scale(0.7);
      transition: all 0.15s ease;
      background-repeat: no-repeat;
      background-position: center;
      background-size: contain;
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 10" fill="none"><path d="M1.5 5.5L4.5 8.5L10.5 1.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
    }
  }

  &__label {
    font-size: 14px;
    color: variables.$text-color;
  }

  &:hover:not(.is-disabled) {
    .ui-checkbox__control {
      border-color: variables.$color-primary;
    }
  }

  &.is-disabled {
    cursor: not-allowed;
    opacity: 0.8;
  }
}
</style>