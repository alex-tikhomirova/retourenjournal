<script setup>

import BrandHeader from "@/components/blocks/BrandHeader.vue";
import LogoChar from "@/components/blocks/LogoChar.vue";
</script>

<template>
  <div class="guest-layout">
    <header>
      <div class="container">
        <div class="flex-row">
          <BrandHeader/>
          <div class="nav"></div>
          <div class="actions">

            <RouterLink class="btn" to="/login">Anmelden</RouterLink>
            <RouterLink class="btn btn-primary" to="/register">Registrieren</RouterLink>

          </div>
        </div>
      </div>
    </header>
    <main>
      <slot/>
    </main>
    <footer>

      <div class="container">

        <div class="flex-row">
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
                <li><a href="#">Überblick</a></li>
                <li><a href="#">Funktionen</a></li>
                <li><a href="#">GitHub</a></li>
              </ul>
            </nav>
            <nav>
              <h3 class="">Rechtliches</h3>
              <ul>
                <li><a href="#">Impressum</a></li>
                <li><a href="#">Datenschutzerklärung</a></li>
              </ul>
            </nav>
            <nav>
              <h3 class="">Support</h3>
              <ul>
                <li><a href="#">Kontakt</a></li>
                <li><a href="#">Hilfe / FAQ</a></li>
                <li><a href="#">Demo anfragen</a></li>
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
      justify-content: space-between;
    }
    a{
      color: #ffffff;
    }
    .container{
      .flex-row{
        display: flex;
        justify-content: space-between;
        .footer-brand{
          flex: 1;
        }
        .footer-nav{
          flex: 3;
          justify-content: space-between;
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