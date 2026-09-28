<script setup>
import ImagePreview from "@/components/ui/ImagePreview.vue";

const img = (name) => `/images/help/${name}`

const tocItems = [
  { id: 'quick-start', title: 'Schnellstart' },
  { id: 'concepts', title: 'Grundbegriffe' },
  { id: 'account-organization', title: 'Konto & Organisation' },
  { id: 'returns', title: 'Retouren' },
  { id: 'returns-list', title: 'Retourenliste' },
  { id: 'statuses', title: 'Status & Bearbeitung' },
  { id: 'refunds', title: 'Erstattungen' },
  { id: 'scenarios', title: 'Typische Abläufe' },
  { id: 'troubleshooting', title: 'Probleme' },
  { id: 'faq', title: 'FAQ' },
]

const conceptItems = [
  ['Organisation', 'Der Arbeitsbereich eines Unternehmens oder Teams. Retouren werden innerhalb einer Organisation verwaltet.'],
  ['Benutzer', 'Eine Person, die im Namen einer Organisation mit Retouren arbeitet.'],
  ['Kunde', 'Die Person, die eine Ware zurücksendet. Name, E-Mail, Telefon und Adresse helfen bei der Bearbeitung.'],
  ['Retoure', 'Die zentrale Akte mit Kundendaten, Referenz, Artikeln, Entscheidung, Versand, Erstattung und Historie.'],
  ['Artikel', 'Ein einzelner zurückgesendeter Artikel mit SKU, Bezeichnung, Seriennummer, Menge, Preis und Währung.'],
  ['Status', 'Der aktuelle Bearbeitungsstand, zum Beispiel erfasst, in Prüfung, freigegeben oder abgeschlossen.'],
  ['Entscheidung', 'Das Ergebnis der Prüfung: genehmigen, ablehnen oder später erneut bearbeiten.'],
  ['Versand & Logistik', 'Informationen zur Rücksendung, Dienstleister, Tracking und Versandstatus.'],
  ['Erstattung', 'Die finanzielle Rückzahlung mit Betrag, Status, Referenz und Datum.'],
  ['Historie', 'Das Protokoll der wichtigsten Änderungen an einer Retoure.'],
]

const lifecycleItems = [
  'Retoure wird erfasst.',
  'Ware wird vom Kunden zurückgesendet.',
  'Ware wird geprüft.',
  'Entscheidung wird gesetzt.',
  'Versand oder Erstattung wird angelegt, falls erforderlich.',
  'Retoure wird abgeschlossen.',
]

const scenarios = [
  {
    title: 'Erste Retoure erstellen',
    steps: [
      'Bereich Retouren öffnen.',
      'Neue Rückgabe anklicken.',
      'Retourdaten und Kundendaten ausfüllen.',
      'Mindestens einen Artikel erfassen.',
      'Retoure speichern.',
    ],
  },
  {
    title: 'Retoure eines Kunden finden',
    steps: [
      'Retourenliste öffnen.',
      'Suchbegriff eingeben: Retourennummer, Bestellreferenz, Kunde oder SKU.',
      'Bei Bedarf Status oder Zeitraum einschränken.',
      'Passende Tabellenzeile öffnen.',
    ],
  },
  {
    title: 'Eingegangene Ware bearbeiten',
    steps: [
      'Retourenkarte öffnen.',
      'Kunde, Artikel und Grund prüfen.',
      'Entscheidung setzen oder prüfen.',
      'Versand und Erstattung dokumentieren.',
      'Historie kontrollieren.',
    ],
  },
]

const faqItems = [
  {
    question: 'Warum sehe ich keine Retouren?',
    answer: 'Entweder gibt es noch keine Retouren in der Organisation oder aktive Filter blenden vorhandene Einträge aus. Setzen Sie zuerst die Filter zurück.',
  },
  {
    question: 'Was ist der Unterschied zwischen Retourenstatus und Erstattungsstatus?',
    answer: 'Der Retourenstatus beschreibt die Bearbeitung der gesamten Retoure. Der Erstattungsstatus beschreibt nur die finanzielle Rückzahlung.',
  },
  {
    question: 'Kann ich eine Retoure ohne vollständige Kundendaten erstellen?',
    answer: 'Pflichtfelder müssen ausgefüllt werden. Zusätzliche Daten wie E-Mail, Telefon und Adresse sind hilfreich, wenn später Rückfragen entstehen.',
  },
  {
    question: 'Wo sehe ich, was mit einer Retoure passiert ist?',
    answer: 'In der Retourenkarte befindet sich der Bereich Historie. Dort werden wichtige Statusänderungen und erstellte Einträge protokolliert.',
  },
]
</script>

<template>
  <div class="help-page container">
    <h1>Hilfe / FAQ</h1>
    <p class="text-muted">Benutzerhandbuch für RetourenJournal.</p>

    <div class="help-layout">
      <aside class="help-toc" aria-label="Inhaltsverzeichnis">
        <div class="toc-panel">
          <div class="toc-title">Inhalt</div>
          <nav>
            <a v-for="item in tocItems" :key="item.id" :href="`#${item.id}`">{{ item.title }}</a>
          </nav>
        </div>
      </aside>

      <main class="help-content">
        <section id="quick-start" class="help-section">
          <h2>Schnellstart</h2>
          <p>RetourenJournal bündelt die Bearbeitung von Rücksendungen an einem Ort: Kundendaten, Bestellreferenz, Artikel, Entscheidung, Versand, Erstattung und Historie.</p>
          <ol>
            <li>Einloggen.</li>
            <li>E-Mail bestätigen, falls die Anwendung dazu auffordert.</li>
            <li>Organisation einrichten oder öffnen.</li>
            <li>Retourenliste öffnen.</li>
            <li>Neue Retoure erstellen oder eine bestehende Retoure bearbeiten.</li>
          </ol>
          <ImagePreview :src="img('01-login.png')" alt="Login in RetourenJournal" caption="Login in RetourenJournal" />
          <ImagePreview :src="img('03-welcome-no-organization.png')" alt="Startseite nach dem Login" caption="Startseite nach dem Login" />
        </section>

        <section id="concepts" class="help-section">
          <h2>Grundbegriffe</h2>
          <p>Die wichtigsten Begriffe helfen dabei, die Oberfläche schneller zu verstehen.</p>
          <dl class="concept-list">
            <template v-for="item in conceptItems" :key="item[0]">
              <dt>{{ item[0] }}</dt>
              <dd>{{ item[1] }}</dd>
            </template>
          </dl>
          <ImagePreview :src="img('10-return-detail-overview.png')" alt="Retourenkarte mit allen zentralen Bereichen" caption="Retourenkarte mit Kundendaten, Artikeln, Entscheidung, Versand, Erstattung und Historie" />
        </section>

        <section id="account-organization" class="help-section">
          <h2>Konto & Organisation</h2>
          <p>Für die Arbeit mit RetourenJournal melden Sie sich mit E-Mail und Passwort an. Ist die E-Mail noch nicht bestätigt, zeigt die Anwendung einen eigenen Hinweis mit der Möglichkeit, die Bestätigungs-E-Mail erneut zu senden.</p>
          <ImagePreview :src="img('02-email-verification.png')" alt="E-Mail-Bestätigung" caption="Hinweis zur E-Mail-Bestätigung" />
          <p>Eine Organisation ist der Arbeitsbereich, in dem Retouren verwaltet werden. Wenn noch keine Organisation vorhanden ist, legen Sie diese zuerst an.</p>
          <ImagePreview :src="img('04-create-organization.png')" alt="Organisation erstellen" caption="Organisation erstellen" />
        </section>

        <section id="returns" class="help-section">
          <h2>Retouren</h2>
          <p>Eine neue Retoure wird über Neue Rückgabe in der Retourenliste erstellt. Die Formularbereiche sind Retourdaten, Kunde und Artikel.</p>
          <ImagePreview :src="img('06-create-return-form.png')" alt="Formular zum Erstellen einer Retoure" caption="Formular zum Erstellen einer Retoure" />
          <p>Im Kundenbereich werden Name, E-Mail, Telefonnummer und Adresse erfasst. Je vollständiger diese Angaben sind, desto leichter lassen sich Rückfragen klären.</p>
          <ImagePreview :src="img('07-create-return-customer.png')" alt="Kundenbereich im Retourenformular" caption="Kundenbereich im Retourenformular" />
          <p>Im Artikelbereich werden die zurückgesendeten Produkte mit SKU, Bezeichnung, Seriennummer, Menge, Preis und Währung dokumentiert.</p>
          <ImagePreview :src="img('08-create-return-items.png')" alt="Artikelbereich im Retourenformular" caption="Artikelbereich im Retourenformular" />
          <p>Wenn Pflichtfelder fehlen, zeigt das Formular Validierungsfehler. Korrigieren Sie die markierten Felder und speichern Sie erneut.</p>
          <ImagePreview :src="img('15-form-validation-error.png')" alt="Validierungsfehler im Retourenformular" caption="Validierungsfehler im Retourenformular" />
        </section>

        <section id="returns-list" class="help-section">
          <h2>Retourenliste</h2>
          <p>Die Retourenliste ist die zentrale Übersicht. Sie zeigt Retourennummer, Referenz, Kunde, Status, Erstellungsdatum und Aktualisierung.</p>
          <ImagePreview :src="img('09-returns-list-filled.png')" alt="Retourenliste mit Demodaten" caption="Retourenliste mit Demodaten" />
          <p>Über Suche, Statusfilter und Zeitraumfilter lässt sich die Liste eingrenzen. Wenn keine Retoure gefunden wird, setzen Sie die Filter zurück oder ändern Sie den Suchbegriff.</p>
          <ImagePreview :src="img('13-returns-filters.png')" alt="Filter in der Retourenliste" caption="Filter in der Retourenliste" />
          <ImagePreview :src="img('14-returns-filtered-empty.png')" alt="Keine Treffer nach Filterung" caption="Keine Treffer nach Filterung" />
          <ImagePreview :src="img('05-returns-list-empty.png')" alt="Leerer Zustand der Retourenliste" caption="Leerer Zustand der Retourenliste" />
        </section>

        <section id="statuses" class="help-section">
          <h2>Status & Bearbeitung</h2>
          <p>Statuslabels zeigen, in welchem Schritt sich eine Retoure befindet. Dadurch ist schnell sichtbar, welche Vorgänge Aufmerksamkeit benötigen.</p>
          <ol>
            <li v-for="item in lifecycleItems" :key="item">{{ item }}</li>
          </ol>
          <ImagePreview :src="img('11-return-status-processing.png')" alt="Entscheidung und Status einer Retoure" caption="Entscheidung und Status einer Retoure" />
          <p>Versand und Logistik dokumentieren Dienstleister, Tracking, Richtung, Zahler und Versandstatus.</p>
          <ImagePreview :src="img('17-shipment-section.png')" alt="Versand und Logistik einer Retoure" caption="Versand und Logistik einer Retoure" />
          <p>Die Historie zeigt wichtige Änderungen chronologisch an.</p>
          <ImagePreview :src="img('16-return-history-notes.png')" alt="Historie einer Retoure" caption="Historie einer Retoure" />
        </section>

        <section id="refunds" class="help-section">
          <h2>Erstattungen</h2>
          <p>Der Bereich Erstattungen zeigt Betrag, Status, Ersteller, Datum und interne Referenz. Eine Retoure ist erst dann finanziell erledigt, wenn die Erstattung passend dokumentiert ist.</p>
          <ImagePreview :src="img('12-refund-section.png')" alt="Erstattungsbereich einer Retoure" caption="Erstattungsbereich einer Retoure" />
        </section>

        <section id="scenarios" class="help-section">
          <h2>Typische Abläufe</h2>
          <div class="scenario-list">
            <section v-for="scenario in scenarios" :key="scenario.title">
              <h3>{{ scenario.title }}</h3>
              <ol>
                <li v-for="step in scenario.steps" :key="step">{{ step }}</li>
              </ol>
            </section>
          </div>
        </section>

        <section id="troubleshooting" class="help-section">
          <h2>Probleme</h2>
          <h3>E-Mail ist nicht bestätigt</h3>
          <p>Öffnen Sie Ihr Postfach und klicken Sie auf den Bestätigungslink. Falls keine E-Mail angekommen ist, senden Sie die Bestätigungs-E-Mail erneut.</p>
          <h3>Retoure wird nicht gefunden</h3>
          <p>Prüfen Sie Suchbegriff und Filter. Setzen Sie die Filter zurück und suchen Sie erneut.</p>
          <h3>Formular lässt sich nicht speichern</h3>
          <p>Kontrollieren Sie die Pflichtfelder. Fehlende oder ungültige Angaben werden im Formular markiert.</p>
        </section>

        <section id="faq" class="help-section">
          <h2>FAQ</h2>
          <div class="faq-list">
            <details v-for="item in faqItems" :key="item.question">
              <summary>{{ item.question }}</summary>
              <p>{{ item.answer }}</p>
            </details>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.help-layout {
  display: grid;
  margin-top: variables.$module-padding;
  grid-template-columns: minmax(0, 1fr) 280px;
  gap: variables.$module-padding;
  align-items: start;
}

.help-content {
  display: grid;
  gap: 44px;
  min-width: 0;
}

.help-toc {
  position: sticky;
  top: 24px;
  order: 2;
}

.toc-panel {
  border-left: 3px solid variables.$color-primary;
  background: variables.$background-color;
  padding: 14px 16px;
}

.toc-title {
  margin-bottom: 12px;
  font-weight: 500;
}

.help-toc nav {
  display: grid;
  gap: 8px;
}

.help-toc a {
  display: block;
  border-radius: variables.$border-radius;
  padding: 7px 8px;
  color: variables.$text-color;
}

.help-toc a:hover {
  background: variables.$head-bg-color;
  color: variables.$color-primary;
}

.help-section {
  scroll-margin-top: 24px;
  display: grid;
  gap: 14px;
  padding-bottom: 34px;
  border-bottom: 1px solid variables.$border-color;
}

.help-section:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.help-section h2,
.help-section h3,
.help-section p,
.help-section ol,
.help-section dl {
  margin: 0;
}

.help-section ol {
  list-style: decimal;
  padding-left: 22px;
}

.help-section li + li {
  margin-top: 4px;
}

.concept-list {
  display: grid;
  grid-template-columns: 180px minmax(0, 1fr);
  gap: 10px 18px;
}

.concept-list dt {
  font-weight: 500;
}

.concept-list dd {
  margin: 0;
  color: variables.$text-color-muted;
}

.scenario-list {
  display: grid;
  gap: 18px;
}

.scenario-list section {
  display: grid;
  gap: 8px;
}

.faq-list {
  display: grid;
  gap: 12px;
}

.faq-list details {
  border-bottom: 1px solid variables.$border-color;
  padding-bottom: 12px;
}

.faq-list details:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.faq-list summary {
  cursor: pointer;
  font-weight: 500;
}

.faq-list p {
  margin-top: 8px;
  color: variables.$text-color-muted;
}

@media (max-width: variables.$breakpoint-lg) {
  .help-layout {
    grid-template-columns: 1fr;
  }

  .help-toc {
    position: static;
    order: -1;
  }

  .concept-list {
    grid-template-columns: 1fr;
  }
}
</style>
