// frontend/src/content/legal/index.js
import privacyDe from './privacy.de.md?raw'
import termsDe from './terms.de.md?raw'
import avvDe from './avv.de.md?raw'
import tomDe from './tom.de.md?raw'
import subprocessorsDe from './subprocessors.de.md?raw'

const legalDocuments = {
    privacy: {
        title: 'Datenschutzerklärung',
        content: privacyDe,
    },
    terms: {
        title: 'Nutzungsbedingungen',
        content: termsDe,
    },
    avv: {
        title: 'Auftragsverarbeitungsvertrag',
        content: avvDe,
    },
    tom: {
        title: 'Technische und organisatorische Maßnahmen',
        content: tomDe,
    },
    subprocessors: {
        title: 'Unterauftragsverarbeiter',
        content: subprocessorsDe,
    },
}

const legalVariables = {
    LEGAL_PROVIDER_NAME: import.meta.env.VITE_LEGAL_PROVIDER_NAME,
    LEGAL_PROVIDER_ADDRESS: import.meta.env.VITE_LEGAL_PROVIDER_ADDRESS,
    LEGAL_PROVIDER_COUNTRY: import.meta.env.VITE_LEGAL_PROVIDER_COUNTRY,
    LEGAL_PRIVACY_EMAIL: import.meta.env.VITE_LEGAL_PRIVACY_EMAIL,
    LEGAL_CONTACT_EMAIL: import.meta.env.VITE_LEGAL_CONTACT_EMAIL,
    HOSTING_PROVIDER: import.meta.env.VITE_LEGAL_HOSTING_PROVIDER,
}

const legalTemplatePattern = /{{([A-Z0-9_]+)}}/g

const fillLegalTemplate = (content) => {
    return content.replace(legalTemplatePattern, (_, key) => {
        return legalVariables[key] ?? `{{${key}}}`
    })
}

export { legalDocuments, fillLegalTemplate }