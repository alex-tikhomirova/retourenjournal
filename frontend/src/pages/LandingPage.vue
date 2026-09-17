<script setup>
import {
  ArchiveRestore,

  Building2,
  ClipboardCheck,
  ClipboardList,
  Euro,
  History,
  PackageCheck,
  SearchCheck,
  ShieldCheck,
  ShoppingBag,
  Store,
  Truck,
} from 'lucide-vue-next'
import {computed} from 'vue'
import {useRoute} from 'vue-router'
import ContactForm from '@/components/forms/ContactForm.vue'
import {useUserStore} from '@/stores/user.js'

const route = useRoute()
const user = useUserStore()

const targetRoute = () => (user.isLoggedIn ? '/app' : '/register')
const contactTopic = computed(() => (
  typeof route.query.contactTopic === 'string' ? route.query.contactTopic : ''
))

const overviewCards = [
  {
    icon: ClipboardList,
    title: 'Ein zentraler Vorgang statt verstreuter Notizen',
    text: 'Jede Retoure bekommt einen eigenen Datensatz mit Kundendaten, Artikeln, Status, Entscheidung, Sendungen, Erstattungen und Verlauf. So bleibt sichtbar, was passiert ist, wer etwas geändert hat und welche Schritte noch offen sind.',
  },
  {
    icon: SearchCheck,
    title: 'Nachvollziehbar, auch wenn der Fall später wieder auftaucht',
    text: 'Retouren verschwinden nicht mehr zwischen E-Mails, Tabellen und Chatverläufen. Statuswechsel, Entscheidungen und wichtige Aktionen werden dokumentiert, damit ein Vorgang auch nach Wochen noch verständlich bleibt.',
  },
  {
    icon: Building2,
    title: 'Bewusst schlank: kein ERP, keine große Einführung',
    text: 'RetourenJournal ersetzt keine Warenwirtschaft und keine Shop-Integration. Es konzentriert sich auf den Bereich, der oft fehlt: ein klares, mandantenfähiges Retourenjournal für kleine Teams und B2B-Prozesse.',
  },
]

const audiences = [
  {
    icon: Store,
    title: 'Online-Shops',
    text: 'Für kleine und mittlere Shops, die Rücksendungen sauber dokumentieren möchten, ohne gleich ein ERP-Projekt zu starten.',
  },
  {
    icon: ShoppingBag,
    title: 'Marktplatz-Verkäufer',
    text: 'Für Etsy-, eBay- und Social-Commerce-Verkäufer, die Bestellungen, Kunden und Rückgaben getrennt vom Postfach verfolgen wollen.',
  },
  {
    icon: Building2,
    title: 'B2B auf Rechnung',
    text: 'Für Unternehmen, die Warenrückgaben, Ersatzlieferungen und Erstattungen nachvollziehbar pro Vorgang festhalten müssen.',
  },
  {
    icon: ShieldCheck,
    title: 'Teams ohne ERP',
    text: 'Für Teams, die Ordnung brauchen, aber keine komplexe Systemlandschaft, Schulung oder Integration bezahlen möchten.',
  },
]

const features = [
  {
    icon: ArchiveRestore,
    title: 'Retouren erfassen',
    text: 'Retourennummer, Kunde, Referenz, Rücksendegrund und Artikel werden in einem strukturierten Vorgang gesammelt.',
    visual: 'return',
  },
  {
    icon: ClipboardCheck,
    title: 'Status & Entscheidungen',
    text: 'Genehmigung, Ablehnung, nächster Status und notwendige Schritte bleiben direkt am Vorgang sichtbar.',
    visual: 'decision',
  },
  {
    icon: Truck,
    title: 'Versand verfolgen',
    text: 'Hin- und Rücksendungen mit Richtung, Zahler, Carrier, Trackingnummer, Kosten und Versandstatus dokumentieren.',
    visual: 'shipment',
  },
  {
    icon: Euro,
    title: 'Erstattungen verwalten',
    text: 'Beträge, Währung, Referenz, Status und Bearbeitungsdatum einer Rückerstattung festhalten.',
    visual: 'refund',
  },
  {
    icon: History,
    title: 'Historie behalten',
    text: 'Wichtige Änderungen werden als Verlauf protokolliert, damit Entscheidungen und Statuswechsel nachvollziehbar bleiben.',
    visual: 'history',
  },
  {
    icon: SearchCheck,
    title: 'Retouren finden',
    text: 'Listen, Suche, Filter, Statusauswahl und gespeicherte Ansichten helfen, offene Vorgänge schnell wiederzufinden.',
    visual: 'search',
  },
]

const workflow = [
  {
    icon: ArchiveRestore,
    title: 'Retoure anlegen',
    text: 'Kundendaten, Artikel und Rücksendegrund in einem neuen Vorgang erfassen.',
  },
  {
    icon: Truck,
    title: 'Sendung verfolgen',
    text: 'Rücksendung mit Carrier, Trackingnummer und aktuellem Versandstatus dokumentieren.',
  },
  {
    icon: ClipboardCheck,
    title: 'Entscheidung treffen',
    text: 'Rückgabe genehmigen oder ablehnen und die nächsten erforderlichen Schritte festlegen.',
  },
  {
    icon: PackageCheck,
    title: 'Erstattung oder Ersatz verfolgen',
    text: 'Rückzahlung oder Ersatzlieferung bis zum erfolgreichen Abschluss nachvollziehen.',
  },
]
</script>

<template>
  <div class="landing-page">
    <section id="ueberblick" class="landing-hero">
      <div class="container">
        <div class="hero-grid">
          <div class="hero-copy">
            <div class="eyebrow">Retourenmanagement für kleine Teams</div>
            <h1>Retouren einfach dokumentieren und nachverfolgen</h1>
            <p class="lead">
              RetourenJournal hilft kleinen Unternehmen, Rücksendungen, Status, Sendungen und Erstattungen
              strukturiert zu verwalten - ohne ERP, ohne Shop-Integration und ohne komplizierte Einrichtung.
            </p>
            <div class="hero-actions">
              <RouterLink class="btn btn-lg btn-primary" :to="targetRoute()">Kostenlos starten</RouterLink>
              <a class="btn btn-lg btn-outline-primary" href="#funktionen">Funktionen ansehen</a>
            </div>
          </div>

          <div class="hero-product" aria-label="RetourenJournal Produktvisualisierung">
            <div class="mock-browser">
              <div class="mock-browser-bar">
                <span></span><span></span><span></span>
                <strong>RetourenJournal</strong>
              </div>
              <div class="mock-app-shell">
                <aside>
                  <div class="mock-logo">R</div>
                  <span class="nav-line active"></span>
                  <span class="nav-line"></span>
                  <span class="nav-line short"></span>
                </aside>
                <main>
                  <div class="mock-title-row">
                    <div>
                      <h2>Retoure RE-0007</h2>
                      <p><span class="dot blue"></span> In Prüfung · zuletzt aktualisiert</p>
                    </div>
                    <button>Abschließen</button>
                  </div>
                  <div class="mock-dashboard">
                    <section class="mock-card return-data">
                      <h3>Retourdaten</h3>
                      <dl>
                        <div><dt>Referenz</dt><dd>ORD-12345</dd></div>
                        <div><dt>Rücksendegrund</dt><dd>Artikel beschädigt</dd></div>
                      </dl>
                    </section>
                    <section class="mock-card decision-card">
                      <h3>Entscheidung bestätigt</h3>
                      <div class="decision-box">
                        <span class="status approved">Genehmigt</span>
                        <strong>Vollerstattung</strong>
                        <small>Wareneingang + Rückerstattung erforderlich</small>
                      </div>
                    </section>
                    <section class="mock-card history-card">
                      <h3>Historie</h3>
                      <p><span class="dot green"></span>Status geändert</p>
                      <p><span class="dot yellow"></span>Sendung angelegt</p>
                      <p><span class="dot pink"></span>Erstattung geplant</p>
                    </section>
                  </div>
                </main>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-section">
      <div class="container">
        <div class="section-heading">
          <h2>Was ist RetourenJournal?</h2>
          <p>
            Ein schlankes Retourenjournal für Teams, die Rücksendungen nicht mehr in E-Mails, Tabellen
            und Notizen verlieren möchten.
          </p>
        </div>
        <div class="overview-grid">
          <article v-for="card in overviewCards" :key="card.title" class="info-card overview-card" tabindex="0">
            <component :is="card.icon"/>
            <h3>{{ card.title }}</h3>
            <p>{{ card.text }}</p>
          </article>
        </div>
      </div>
    </section>

    <section id="funktionen" class="landing-section section-muted">
      <div class="container">
        <div class="section-heading">
          <h2>Funktionen</h2>
          <p>Sechs Kernbereiche bilden den MVP ab: vom Erfassen der Retoure bis zum schnellen Wiederfinden offener Vorgänge.</p>
        </div>
        <div class="features-grid">
          <article v-for="feature in features" :key="feature.title" class="feature-card interactive-card" tabindex="0">
            <div class="feature-visual" :class="`visual-${feature.visual}`">
              <div class="visual-header">
                <component :is="feature.icon"/>
                <h3>{{ feature.title }}</h3>
              </div>

              <div v-if="feature.visual === 'return'" class="visual-ui visual-return">
                <div class="field wide"></div>
                <div class="field"></div>
                <div class="field"></div>
                <div class="mini-table"><span></span><span></span><span></span></div>
              </div>
              <div v-else-if="feature.visual === 'decision'" class="visual-ui visual-decision">
                <div class="decision-panel good">Genehmigung</div>
                <div class="decision-panel next">Nächster Status</div>
                <div class="decision-actions"><span></span><span></span></div>
              </div>
              <div v-else-if="feature.visual === 'shipment'" class="visual-ui visual-shipment">
                <div class="route-line"><span></span><i></i><span></span></div>
                <div class="track-card">DHL · 003404...</div>
                <div class="status-track"><span></span><span></span><span></span></div>
              </div>
              <div v-else-if="feature.visual === 'refund'" class="visual-ui visual-refund">
                <div class="amount">120,00 €</div>
                <div class="refund-status">Ausstehend</div>
                <div class="refund-bars"><span></span><span></span></div>
              </div>
              <div v-else-if="feature.visual === 'history'" class="visual-ui visual-history">
                <div class="event"><span class="dot green"></span>Status geändert</div>
                <div class="event"><span class="dot blue"></span>Sendung angelegt</div>
                <div class="event"><span class="dot pink"></span>Erstattung erstellt</div>
              </div>
              <div v-else class="visual-ui visual-search">
                <div class="search-input"></div>
                <div class="filter-row"><span></span><span></span><span></span></div>
                <div class="result-lines"><i></i><i></i><i></i></div>
              </div>
            </div>
            <p>{{ feature.text }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="landing-section trust-section">
      <div class="container">
        <div class="trust-card interactive-card" tabindex="0">
          <ShieldCheck/>
          <div>
            <h2>Mandantenfähige Datenstruktur</h2>
            <p>
              Daten werden organisationsbezogen getrennt. Für B2B-Nutzung stehen Datenschutzhinweise,
              Nutzungsbedingungen und AVV bereit.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-section audience-section section-muted">
      <div class="container">
        <div class="section-heading">
          <h2>Für wen ist es gedacht?</h2>
          <p>
            RetourenJournal passt zu Unternehmen, die Rücksendungen kontrollieren müssen, aber keine große
            Systemeinführung planen.
          </p>
        </div>
        <div class="audience-grid">
          <article v-for="item in audiences" :key="item.title" class="audience-card interactive-card" tabindex="0">
            <component :is="item.icon"/>
            <h3>{{ item.title }}</h3>
            <p>{{ item.text }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="landing-section workflow-section">
      <div class="container">
        <div class="section-heading">
          <h2>Workflow</h2>
          <p>Vom Eingang der Retoure bis zur Rückzahlung oder Ersatzlieferung bleibt jeder Schritt nachvollziehbar.</p>
        </div>
        <div class="workflow-grid">
          <article v-for="step in workflow" :key="step.title" class="workflow-card interactive-card" tabindex="0">
            <component :is="step.icon"/>
            <h3>{{ step.title }}</h3>
            <p>{{ step.text }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="landing-section final-cta section-muted">
      <div class="container">
        <div class="cta-card interactive-card">
          <PackageCheck/>
          <div>
            <h2>Kostenlos starten oder Anpassung anfragen</h2>
            <p>
              Nutzen Sie RetourenJournal direkt im Browser. Wenn Ihr Prozess besondere Anforderungen hat,
              kann eine individuelle Anpassung separat besprochen werden.
            </p>
          </div>
          <div class="cta-actions">
            <RouterLink class="btn btn-lg btn-primary" :to="targetRoute()">Kostenlos starten</RouterLink>
            <RouterLink
                class="btn btn-lg btn-outline-primary"
                :to="{path: '/', query: {contactTopic: 'adjustment'}, hash: '#kontakt'}"
            >
              Anpassung anfragen
            </RouterLink>
          </div>
        </div>

        <div id="kontakt" class="contact-panel">
          <div class="contact-copy">
            <h2>Kontakt</h2>
            <p>
              Schreiben Sie uns bei Fragen zu RetourenJournal, technischen Problemen oder individuellen
              Anforderungen. Wir antworten an die angegebene E-Mail-Adresse.
            </p>
          </div>
          <ContactForm :topic="contactTopic"/>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";
@use "sass:color";

$soft-shadow: 0 12px 30px rgba(16, 24, 40, 0.08);
$card-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);

.landing-page {
  background: variables.$background-body-color;
  color: variables.$text-color;

  p {
    color: variables.$text-color-muted;
    line-height: 1.6;
  }

  svg {
    width: 24px;
    height: 24px;
    color: variables.$color-primary;
  }
}

.landing-hero,
.landing-section {
  padding-left: 20px;
  padding-right: 20px;
}

.landing-hero {
  padding-top: 76px;
  padding-bottom: 58px;
}

.landing-section {
  padding-top: 58px;
  padding-bottom: 58px;
}

.section-muted {
  background: #fff;
  border-top: 1px solid variables.$border-color;
  border-bottom: 1px solid variables.$border-color;
}

.section-heading {
  max-width: 780px;
  margin-bottom: 32px;

  h2 {
    font-size: 1.8rem;
    margin-bottom: 12px;
  }

  p {
    font-size: 1.04rem;
    margin: 0;
  }
}

.hero-grid {
  display: grid;
  grid-template-columns: minmax(0, 0.92fr) minmax(480px, 1.08fr);
  gap: 46px;
  align-items: center;
}

.hero-copy {
  max-width: 700px;

  .eyebrow {
    color: variables.$color-primary;
    font-weight: 500;
    margin-bottom: 14px;
  }

  h1 {
    font-size: 3.1rem;
    line-height: 1.08;
    font-weight: 700;
    margin-bottom: 20px;
  }

  .lead {
    font-size: 1.12rem;
    max-width: 650px;
  }
}

.hero-actions,
.cta-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 28px;
}

.mock-browser,
.info-card,
.feature-card,
.audience-card,
.trust-card,
.cta-card,
.workflow-card {
  background: #fff;
  border: 1px solid variables.$border-color;
  border-radius: variables.$border-radius;
  box-shadow: $card-shadow;
}

.hero-product {
  transition: transform 160ms ease, filter 160ms ease;

  &:hover {
    transform: translateY(-4px);
    filter: saturate(1.03);
  }
}

.mock-browser {
  overflow: hidden;
  box-shadow: $soft-shadow;
}

.mock-browser-bar {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 11px 14px;
  border-bottom: 1px solid variables.$border-color;
  background: #f9fafb;

  span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #d1d5db;
  }

  strong {
    margin-left: 8px;
    font-size: 0.8rem;
    color: variables.$text-color-muted;
  }
}

.mock-app-shell {
  display: grid;
  grid-template-columns: 90px 1fr;
  min-height: 430px;
  background: variables.$background-body-color;

  aside {
    padding: 18px 14px;
    background: #fff;
    border-right: 1px solid variables.$border-color;
  }

  main {
    padding: 20px;
  }
}

.mock-logo {
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: variables.$color-primary;
  color: #fff;
  border-radius: variables.$border-radius;
  font-weight: 700;
  margin-bottom: 28px;
}

.nav-line {
  display: block;
  height: 9px;
  border-radius: 999px;
  background: #e5e7eb;
  margin-bottom: 12px;

  &.active {
    background: color.mix(#fff, variables.$color-primary, 70%);
  }

  &.short {
    width: 70%;
  }
}

.mock-title-row {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 18px;

  h2 {
    font-size: 1.25rem;
    margin-bottom: 5px;
  }

  p {
    margin: 0;
    font-size: 0.88rem;
  }

  button {
    align-self: start;
    border: 1px solid color.mix(#000, variables.$color-primary, 12%);
    background: variables.$color-primary;
    color: #fff;
    border-radius: variables.$border-radius;
    padding: 8px 12px;
  }
}

.mock-dashboard {
  display: grid;
  grid-template-columns: 0.9fr 1.25fr 0.75fr;
  gap: 14px;
}

.mock-card {
  background: #fff;
  border: 1px solid variables.$border-color;
  border-radius: variables.$border-radius;
  padding: 15px;

  h3 {
    font-size: 0.95rem;
    margin-bottom: 14px;
  }
}

.return-data dl {
  display: grid;
  gap: 12px;

  div {
    border-bottom: 1px solid variables.$border-color;
    padding-bottom: 10px;
  }

  dt {
    font-size: 0.78rem;
    color: variables.$text-color-muted;
  }

  dd {
    margin: 5px 0 0;
    font-weight: 500;
  }
}

.decision-box {
  display: grid;
  gap: 10px;
  padding: 14px;
  background: #eff6ff;
  border: 1px solid #93c5fd;
  border-radius: variables.$border-radius;
}

.status {
  display: inline-flex;
  width: fit-content;
  border-radius: variables.$border-radius;
  padding: 3px 10px;
  font-size: 0.82rem;
  font-weight: 500;
}

.approved {
  background: #dcfce7;
  color: #16a34a;
  border: 1px solid #86efac;
}

.history-card p {
  margin: 0 0 12px;
  font-size: 0.88rem;
}

.dot {
  display: inline-block;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  margin-right: 7px;
}

.blue { background: #60a5fa; }
.green { background: #86efac; }
.yellow { background: #facc15; }
.pink { background: #f0a7c8; }

.overview-grid,
.features-grid,
.audience-grid {
  display: grid;
  gap: 20px;
}

.overview-grid,
.features-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

.audience-grid {
  grid-template-columns: repeat(4, minmax(0, 1fr));
}

.info-card,
.audience-card {
  min-height: 285px;
  padding: 28px;

  > svg {
    width: 42px;
    height: 42px;
  }

  h3 {
    font-size: 1.2rem;
    line-height: 1.25;
    margin: 22px 0 14px;
  }

  p {
    margin: 0;
  }
}

.interactive-card,
.info-card,
.workflow-card {
  transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease, background 160ms ease;

  &:hover,
  &:focus-visible {
    transform: translateY(-4px);
    box-shadow: $soft-shadow;
    border-color: color.mix(#fff, variables.$color-primary, 55%);
    outline: none;
  }
}

.feature-card {
  overflow: hidden;

  > p {
    margin: 0;
    padding: 20px 22px 22px;
  }
}

.feature-visual {
  min-height: 250px;
  padding: 22px;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
  border-bottom: 1px solid variables.$border-color;

  &::after {
    content: "";
    position: absolute;
    right: -44px;
    top: -44px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(37, 111, 168, 0.08);
  }
}

.visual-header {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 20px;

  svg {
    width: 46px;
    height: 46px;
    padding: 9px;
    background: #fff;
    border: 1px solid variables.$border-color;
    border-radius: variables.$border-radius;
    box-shadow: $card-shadow;
  }

  h3 {
    font-size: 1.45rem;
    line-height: 1.05;
    margin: 2px 0 0;
    max-width: 230px;
  }
}

.visual-ui {
  position: relative;
  z-index: 1;
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(221, 221, 221, 0.9);
  border-radius: variables.$border-radius;
  padding: 14px;
  box-shadow: $card-shadow;
}

.field,
.search-input {
  height: 28px;
  border: 1px solid variables.$border-color;
  border-radius: variables.$border-radius;
  background: #fff;
  margin-bottom: 9px;
}

.field.wide { width: 100%; }
.field:not(.wide) { width: 72%; }

.mini-table,
.result-lines {
  display: grid;
  gap: 7px;
  margin-top: 12px;

  span,
  i {
    height: 8px;
    border-radius: 999px;
    background: color.mix(#fff, variables.$color-primary, 78%);
  }
}

.decision-panel,
.track-card,
.refund-status,
.event {
  padding: 10px 12px;
  border-radius: variables.$border-radius;
  margin-bottom: 10px;
  background: #fff;
  border: 1px solid variables.$border-color;
  font-weight: 500;
}

.decision-panel.good { color: #16a34a; border-color: #86efac; background: #f0fdf4; }
.decision-panel.next { color: variables.$color-primary; border-color: #93c5fd; background: #eff6ff; }

.decision-actions,
.filter-row,
.status-track,
.refund-bars {
  display: flex;
  gap: 8px;

  span {
    flex: 1;
    height: 26px;
    border-radius: variables.$border-radius;
    background: color.mix(#fff, variables.$color-primary, 82%);
    border: 1px solid color.mix(#fff, variables.$color-primary, 65%);
  }
}

.route-line {
  display: grid;
  grid-template-columns: 24px 1fr 24px;
  align-items: center;
  margin-bottom: 15px;

  span {
    height: 24px;
    border-radius: 50%;
    background: variables.$color-primary;
  }

  i {
    border-top: 2px dashed color.mix(#fff, variables.$color-primary, 45%);
  }
}

.amount {
  font-size: 2.1rem;
  font-weight: 700;
  color: variables.$color-primary;
  margin-bottom: 12px;
}

.refund-status {
  width: fit-content;
  color: #b1900b;
  border-color: #fde68a;
  background: #fef3c7;
}

.visual-history .event {
  font-size: 0.92rem;
}

.trust-section {
  padding-top: 42px;
  padding-bottom: 42px;
}

.trust-card {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 20px;
  align-items: center;
  padding: 26px 28px;
  background: color.mix(#fff, variables.$color-primary, 94%);
  border-color: color.mix(#fff, variables.$color-primary, 65%);

  > svg {
    width: 44px;
    height: 44px;
  }

  h2 {
    margin-bottom: 8px;
  }

  p {
    margin: 0;
    max-width: 840px;
  }
}

.audience-card {
  background: linear-gradient(180deg, #fff 0%, #f9fafb 100%);
}

.workflow-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
}

.workflow-card {
  min-height: 285px;
  padding: 28px;

  > svg {
    width: 42px;
    height: 42px;
  }

  h3 {
    font-size: 1.2rem;
    line-height: 1.25;
    margin: 22px 0 14px;
  }

  p {
    margin: 0;
  }
}

.final-cta {
  padding-bottom: 70px;
}

.cta-card {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 20px;
  align-items: center;
  padding: 28px;

  > svg {
    width: 42px;
    height: 42px;
  }

  h2 {
    margin-bottom: 8px;
  }

  p {
    margin: 0;
  }
}

.contact-panel {
  scroll-margin-top: 24px;
  display: grid;
  grid-template-columns: minmax(240px, 0.75fr) minmax(0, 1.25fr);
  gap: 42px;
  align-items: start;
  margin-top: 28px;
  padding: 36px;
  background: #fff;
  border: 1px solid variables.$border-color;
  border-radius: variables.$border-radius;
  box-shadow: $card-shadow;

  :deep(.contact-fields) {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  :deep(.contact-field-wide) {
    grid-column: 1 / -1;
  }
}

.contact-copy {
  h2 {
    font-size: 1.8rem;
    margin-bottom: 12px;
  }

  p {
    margin: 0;
  }
}
@media (max-width: 1100px) {
  .hero-grid,
  .cta-card {
    grid-template-columns: 1fr;
  }

  .audience-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .workflow-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 900px) {
  .contact-panel {
    grid-template-columns: 1fr;
  }

  .mock-app-shell {
    grid-template-columns: 1fr;

    aside {
      display: none;
    }
  }

  .mock-dashboard,
  .overview-grid,
  .features-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 640px) {
  .landing-hero {
    padding-top: 42px;
  }

  .hero-copy h1 {
    font-size: 2.2rem;
  }

  .mock-dashboard,
  .overview-grid,
  .features-grid,
  .audience-grid,
  .workflow-grid,
  .trust-card {
    grid-template-columns: 1fr;
  }

  .contact-panel {
    padding: 24px;

    :deep(.contact-fields) {
      grid-template-columns: 1fr;
    }
  }

  .contact-panel :deep(.contact-field-wide) {
    grid-column: auto;
  }

  .mock-title-row {
    display: block;

    button {
      margin-top: 12px;
    }
  }
}
</style>
