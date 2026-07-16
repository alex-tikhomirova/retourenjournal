<script setup>
  import {onBeforeUnmount, onMounted, ref, useSlots} from "vue";
  import {X} from "lucide-vue-next";

  const props = defineProps({
    title: String,
    dismissable: {
      type: Boolean,
      default: true
    },
    size: {
      type: String,
      default: "md",
      validator: (value) => ["sm", "md", "lg", "xl"].includes(value)
    }
  })

  const emit = defineEmits(["close"])
  const slots = useSlots()
  const teleportTarget = ref(null)
  let createdTarget = null

  const close = () => {
    if (!props.dismissable) {
      return
    }

    emit("close")
  }

  onMounted(() => {
    let target = document.getElementById("modal-root")

    if (!target) {
      target = document.createElement("div")
      target.id = "modal-root"
      document.body.appendChild(target)
      createdTarget = target
    }

    teleportTarget.value = target
  })

  onBeforeUnmount(() => {
    if (createdTarget && !createdTarget.hasChildNodes()) {
      createdTarget.remove()
    }
  })
</script>

<template>
  <Teleport v-if="teleportTarget" :to="teleportTarget">
    <div class="modal-overlay" @click.self="close">
      <div class="modal-window" :class="`modal-window-${size}`">
        <div class="modal-header" v-if="title">
          <div class="modal-title">
            {{ title }}
          </div>
          <button
              v-if="dismissable"
              class="modal-close"
              type="button"
              aria-label="Close"
              @click="close"
          >
            <X :size="18"/>
          </button>
        </div>

        <div class="modal-body">
          <slot/>
        </div>

        <div class="modal-footer" v-if="slots.footer">
          <slot name="footer"/>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<style lang="scss">
@use "./../assets/scss/variables";

  .modal-overlay{
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 8vh 18px 18px;
    background: rgba(23, 23, 23, 0.35);
  }

  .modal-window{
    width: 100%;
    max-height: calc(100vh - 36px);
    overflow: hidden;
    border: 1px solid variables.$border-color;
    border-radius: variables.$border-radius;
    background: variables.$background-color;
    display: flex;
    flex-direction: column;
  }

  .modal-window-sm{
    max-width: 420px;
  }

  .modal-window-md{
    max-width: 640px;
  }

  .modal-window-lg{
    max-width: 860px;
  }

  .modal-window-xl{
    max-width: 1100px;
  }

  .modal-header{
    border-bottom: 1px solid variables.$border-color;
    border-top-right-radius: variables.$border-radius;
    border-top-left-radius: variables.$border-radius;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
  }

  .modal-title{
    font-weight: 500;
  }

  .modal-close{
    width: 28px;
    height: 28px;
    border: 0;
    border-radius: variables.$border-radius;
    background: transparent;
    color: variables.$text-color-muted;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;

    &:hover{
      background: variables.$head-bg-color;
      color: variables.$text-color;
    }
  }

  .modal-body{
    padding: variables.$module-padding;
    overflow: auto;
  }

  .modal-footer{
    border-top: 1px solid variables.$border-color;
    padding: 12px 18px;
    background: variables.$head-bg-color;
  }

  @media (max-width: 640px){
    .modal-overlay{
      align-items: stretch;
      padding: 12px;
    }

    .modal-window{
      max-width: none;
      max-height: none;
    }
  }
</style>
