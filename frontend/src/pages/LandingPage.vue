<script setup>
import {useUserStore} from '@/stores/user.js'

const user = useUserStore()
</script>

<template>
  <div class="landing-page">
    <div class="page-body">
      <div class="top-frame">
        <div class="container">
          <div class="flex items-center gap-30">
            <div class="left">
              <h1 class="h1 ">
                Retouren klar steuern <br/>– ohne komplexe Systeme
              </h1>
              <div class="text">
                <p><span class="font-bold">Retourenjournal</span> ist ein leichtgewichtiges B2B-Tool zur strukturierten Verwaltung von
                  Warenrücksendungen.
                  Es hilft Unternehmen, Retourenprozesse übersichtlich abzubilden — ohne ERP-Einführung, ohne
                  komplizierte Integrationen und ohne langen Schulungsaufwand.</p>

                <p>Statt Excel-Listen, E-Mails und verstreuter Notizen erhalten Sie einen zentralen Überblick:
                  Retouren, Artikel, Status, Lieferungen und Rückerstattungen — klar dokumentiert und jederzeit
                  nachvollziehbar.</p>
              </div>
              <div class="buttons flex gap-10">
                <RouterLink class="btn btn-lg btn-outline-primary" to="/login">Anmelden</RouterLink>
                <RouterLink class="btn btn-lg btn-primary" to="/register">Registrieren</RouterLink>
              </div>
            </div>
            <div class="right">
              <img src="/images/landing/retuoren-1.png" alt="retourenjournal"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.landing-page{
  font-size: 1rem;
  p{
    margin: 10px 0;
  }
  .top-frame{
    h1{
      font-size: 3rem;
      line-height: 3rem;
      font-weight: 700;
    }
    .text{
      margin:  40px 0;
    }
  }
}

</style>
