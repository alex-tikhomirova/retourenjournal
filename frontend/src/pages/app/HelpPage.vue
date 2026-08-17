<script setup>
import PageCard from "@/components/PageCard.vue";
import ToolBar from "@/components/ToolBar.vue";

const helpTopics = [
  {
    title: 'Retouren erfassen',
    text: 'Legen Sie eine neue Retoure an, ergänzen Sie Kundendaten, Artikel und Referenzen und speichern Sie den Vorgang als zentrale Akte.',
  },
  {
    title: 'Status verfolgen',
    text: 'Nutzen Sie die Retourenliste, Filter und Statuslabels, um offene Vorgänge schnell zu finden und den nächsten Schritt zu planen.',
  },
  {
    title: 'Rückerstattung dokumentieren',
    text: 'Halten Sie Entscheidungen, Versandinformationen und Erstattungen nachvollziehbar an der Retoure fest.',
  },
]

const faqItems = [
  {
    question: 'Wie lege ich eine neue Retoure an?',
    answer: 'Öffnen Sie den Bereich Retouren und klicken Sie auf "Neue Rückgabe". Danach können Sie die wichtigsten Daten zum Kunden, zur Bestellung und zu den Artikeln erfassen.',
  },
  {
    question: 'Warum sehe ich noch keine Retouren?',
    answer: 'Die Liste bleibt leer, bis die erste Retoure erstellt wurde. Wenn Filter aktiv sind, setzen Sie diese zurück, um alle vorhandenen Einträge zu sehen.',
  },
  {
    question: 'Kann ich eine Retoure später bearbeiten?',
    answer: 'Ja. Öffnen Sie eine Retoure aus der Liste, prüfen Sie die Details und ergänzen Sie fehlende Informationen dort, wo der Vorgang es vorsieht.',
  },
  {
    question: 'Was bedeutet der Status einer Retoure?',
    answer: 'Der Status zeigt, in welchem Bearbeitungsschritt sich die Retoure befindet. So bleiben Prüfung, Entscheidung, Versand und Erstattung leichter nachvollziehbar.',
  },
]
</script>

<template>
  <div class="help-page">
    <ToolBar title="Hilfe / FAQ" subtitle="Ein einfacher Überblick für die Arbeit mit RetourenJournal." />

    <section class="help-grid">
      <PageCard v-for="topic in helpTopics" :key="topic.title" class="padded">
        <h2>{{ topic.title }}</h2>
        <p class="text-muted">{{ topic.text }}</p>
      </PageCard>
    </section>

    <PageCard title="Häufige Fragen" class="faq-card padded">
      <div class="faq-list">
        <details v-for="item in faqItems" :key="item.question" class="faq-item">
          <summary>{{ item.question }}</summary>
          <p class="text-muted">{{ item.answer }}</p>
        </details>
      </div>
    </PageCard>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.help-page {
  display: grid;
  gap: variables.$module-padding;
}

.help-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: variables.$module-padding;
}

.help-grid h2 {
  margin-bottom: 8px;
}

.faq-card {
  .faq-list {
    display: grid;
    gap: 12px;
  }

  .faq-item {
    border-bottom: 1px solid variables.$border-color;
    padding-bottom: 12px;

    &:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    summary {
      cursor: pointer;
      font-weight: 500;
    }

    p {
      margin-top: 8px;
    }
  }
}
</style>
