<script setup>

const contact = {
  name: import.meta.env.VITE_IMPRESSUM_NAME || '[Vorname Nachname]',
  street: import.meta.env.VITE_IMPRESSUM_STREET || '[Straße Hausnummer]',
  postalCode: import.meta.env.VITE_IMPRESSUM_POSTAL_CODE || '[PLZ]',
  city: import.meta.env.VITE_IMPRESSUM_CITY || '[Ort]',
  email: import.meta.env.VITE_IMPRESSUM_EMAIL || '[email]',
}
</script>

<template>
  <div class="impressum-page container ">
    <h1>Impressum</h1>
      <div class="grid gap-24">
        <section class="grid gap-12">
          <h3>Angaben gemäß § 5 DDG</h3>
          <address>
            {{ contact.name }}<br>
            {{ contact.street }}<br>
            {{ contact.postalCode }} {{ contact.city }}<br>
            Deutschland
          </address>
        </section>

        <section class="grid gap-12">
          <h3>Kontakt</h3>
          <p>E-Mail: {{ contact.email }}</p>
        </section>

        <section class="grid gap-12">
          <h3>Streitbeilegung</h3>
          <p>
            Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer
            Verbraucherschlichtungsstelle teilzunehmen.
          </p>
        </section>
      </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables";

.impressum-page {
  padding: 40px variables.$module-padding;
}

address {
  font-style: normal;
  line-height: 1.6;
}
</style>
