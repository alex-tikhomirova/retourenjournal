<script setup>

import BrandHeader from "@/components/blocks/BrandHeader.vue";
</script>

<template>
  <div class="app-layout ">
    <header>
      <div class="container-full">
        <div class="flex-row">
          <BrandHeader/>
          <div class="nav column">

          </div>
          <div class="actions column">

          </div>
        </div>
      </div>

    </header>
    <main>
      <div class="container-full">
        <slot/>
      </div>
    </main>

    <footer>
      <div class="container-full">

        <div class="flex-row">
          <div class="footer-brand ">
            <div class="text ">
              © 2026 RetourenJournal
            </div>
          </div>
          <div class="footer-nav flex">
            <nav>
              <ul>
                <li><a href="#">Hilfe / FAQ</a></li>
                <li><a href="#">Kontakt</a></li>
                <li><a href="#">Impressum</a></li>
                <li><a href="#">Datenschutzerklärung</a></li>
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
.app-layout{
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  header{
    border-bottom: 1px solid variables.$border-color;
    padding: 10px 0;
  }
  main{
    flex: 1;
    padding-top: 24px;
    padding-bottom: 24px;
  }


  header{
    .container-full{
      .flex-row{
        display: flex;
        >*{
          flex: 1;
        }
      }
    }

  }


  footer{
    padding: 20px 0;
    background-color: #101828;
    color: variables.$text-color-light;
    a{
      color: #ffffff;
    }
    .container-full{
      .flex-row{
        display: flex;

        gap: 50px;
        .footer-brand{

        }
        .footer-nav{

          nav{
            ul{
              display: flex;
              gap: 40px;
              li{

              }
            }
          }

        }
      }
    }
  }
}
</style>