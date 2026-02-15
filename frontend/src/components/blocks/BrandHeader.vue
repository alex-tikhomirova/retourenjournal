<script setup>
import {useRoute, useRouter} from "vue-router";
import LogoChar from "@/components/blocks/LogoChar.vue";

  const route = useRoute()
  const router = useRouter()

</script>

<template>
  <router-link class="brand" to="/">
    <LogoChar :size="40"/>
    <div class="title">
      <span><span class="prefix">Retouren</span>journal</span>
    </div>
  </router-link>
</template>

<style lang="scss">
@use "@/assets/scss/variables";
.brand{
  display: flex;
  align-items: center;
  gap: 15px;
  .title{
    font-size: 1.2rem;
    font-weight: 700;
    color: #7e7e7e;
    .prefix{

      color: variables.$color-primary;

    }
  }
}
</style>