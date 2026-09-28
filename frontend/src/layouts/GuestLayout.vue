<script setup>

import BrandHeader from "@/components/blocks/BrandHeader.vue";
import LogoChar from "@/components/blocks/LogoChar.vue";
import {useUserStore} from "@/stores/user.js";

const userStore = useUserStore()
</script>

<template>
  <div class="guest-layout">
    <header>
      <div class="container">
        <div class="flex-row">
          <BrandHeader/>
          <div class="nav"></div>
          <div class="actions">
            <RouterLink class="btn btn-primary" to="/app" v-if="userStore.isLoggedIn">Zur App</RouterLink>
            <template v-else>
              <RouterLink class="btn" to="/login">Anmelden</RouterLink>
              <RouterLink class="btn btn-primary" to="/register">Registrieren</RouterLink>
            </template>
          </div>
        </div>
      </div>
    </header>
    <main>
      <slot/>
    </main>
    <footer>

      <div class="container">

        <div class="flex-row" >
          <div class="footer-brand ">
            <div class="wrapper">
              <LogoChar :size="50"/>
              <div class="text ">
                © 2026 RetourenJournal
              </div>
            </div>
          </div>
          <div class="footer-nav flex">
            <nav>
              <h3 class="">Projekt</h3>
              <ul>
                <li><RouterLink :to="{path: '/', hash: '#ueberblick'}">Überblick</RouterLink></li>
                <li><RouterLink :to="{path: '/', hash: '#funktionen'}">Funktionen</RouterLink></li>
                <li><a href="https://github.com/alex-tikhomirova/retourenjournal">GitHub</a></li>
              </ul>
            </nav>
            <nav>
              <h3 class="">Rechtliches</h3>
              <ul>
                <li><RouterLink to="/impressum">Impressum</RouterLink></li>
                <li><RouterLink to="/legal/privacy">Datenschutzerklärung</RouterLink></li>
                <li><RouterLink to="/legal/terms">Nutzungsbedingungen</RouterLink></li>
              </ul>
            </nav>
            <nav>
              <h3 class="">Support</h3>
              <ul>
                <li><RouterLink :to="{path: '/', hash: '#kontakt'}">Kontakt</RouterLink></li>
                <li><RouterLink to="/help">Hilfe / FAQ</RouterLink></li>
                <li><RouterLink :to="{path: '/', query: {contactTopic: 'adjustment'}, hash: '#kontakt'}">Anpassung anfragen</RouterLink></li>
              </ul>
            </nav>
          </div>
        </div>

      </div>

    </footer>
  </div>

</template>

<style lang="scss">
@use "@/assets/scss/variables";
.guest-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  header {
    border-bottom: 1px solid variables.$border-color;
    padding: 10px 0;
    .container {
      .flex-row {
        display: flex;
        .actions{
          display: flex;
          justify-content: flex-end;
          gap: 10px;
        }
        > * {
          flex: 1;
        }

      }

    }
  }
  main{
    padding-top: 40px;
    padding-bottom: 40px;
    flex: 1;
  }

  footer{
    padding: 20px 0;
    background-color: #101828;
    color: variables.$text-color-light;
    .wrapper{
      display: flex;
      flex-direction: column;
      height: 100%;
      @media (max-width: variables.$breakpoint-sm) {
        flex-direction: column;
      }
    }
    a{
      color: #ffffff;
    }
    .container{
      .flex-row{
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 24px;
        @media (max-width: variables.$breakpoint-sm) {
          flex-direction: column;
          svg{
            height: 24px;
          }
        }
        .footer-brand{
          flex: 1;

        }
        .footer-nav{
          flex: 3;
          justify-content: space-between;
          gap: 24px;
          flex-wrap: wrap;
          h3{
            margin-bottom: 12px;
          }
          li{
            margin-bottom: 6px;
          }
        }
      }
    }
  }
}
</style>

