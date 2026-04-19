<script setup>
const model = defineModel()

defineProps({
  value: {
    type: [String, Number, Boolean, Object, null],
    required: true
  },
  name: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  disabled: Boolean
})
</script>

<template>
  <label class="ui-radio" :class="{ 'is-disabled': disabled }">
    <input
        class="ui-radio__input"
        type="radio"
        :name="name"
        :value="value"
        v-model="model"
        :disabled="disabled"
    />

    <span class="ui-radio__control"></span>

    <span v-if="$slots.default || label" class="ui-radio__label">
      <slot>{{ label }}</slot>
    </span>
  </label>
</template>

<style lang="scss">
@use "@/assets/scss/variables" ;

.ui-radio {
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

    &:focus-visible + .ui-radio__control {
      box-shadow: 0 0 0 3px rgba(variables.$color-primary, 0.25);
    }

    &:checked + .ui-radio__control {
      border-color: variables.$color-primary;

      &::after {
        transform: scale(1);
      }
    }

    &:disabled + .ui-radio__control {
      background: variables.$color-disabled-bg;
      border-color: variables.$color-disabled-border;
    }

    &:disabled ~ .ui-radio__label {
      color: variables.$color-disabled-fg;
    }
  }

  &__control {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid variables.$border-color;
    background: variables.$background-color;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;

    &::after {
      content: "";
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: variables.$color-primary;
      transform: scale(0);
      transition: transform 0.15s ease;
    }
  }

  &__label {
    font-size: 14px;
    color: variables.$text-color;
  }

  &:hover:not(.is-disabled) {
    .ui-radio__control {
      border-color: variables.$color-primary;
    }
  }

  &.is-disabled {
    cursor: not-allowed;
    opacity: 0.8;
  }
}
</style>