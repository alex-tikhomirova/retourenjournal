# Unterauftragsverarbeiter

Stand: [Datum eintragen]
Version: 0.1

Dieses Dokument listet die Unterauftragsverarbeiter und technischen Dienstleister auf, die im Rahmen des Betriebs von RetourenJournal eingesetzt werden oder eingesetzt werden können.

Unterauftragsverarbeiter werden nur eingesetzt, soweit dies für Bereitstellung, Betrieb, Sicherheit, Wartung oder Kommunikation erforderlich ist.

## 1. Aktuelle und geplante Dienstleister

| Dienstleister | Zweck | Verarbeitete Daten | Ort der Verarbeitung | Status |
| --- | --- | --- | --- | --- |
| {{HOSTING_PROVIDER}} | Hosting der Website, Anwendung, Datenbank und technischen Infrastruktur | Konto-, Organisations-, Retouren-, Kunden-, Versand-, Erstattungs-, Log- und technische Daten | Europäische Union | eingesetzt |
| {{MAIL_SERVER_PROVIDER}} | Versand von System-E-Mails und Annahme oder Weiterleitung eingehender Nachrichten | E-Mail-Adressen, Inhalte von System-E-Mails, Kontaktanfragen, technische Mail-Logs | Europäische Union | eingesetzt |
| {{INCOMING_MAIL_PROVIDER}} | Speicherung weitergeleiteter eingehender Kontaktanfragen | Absender, Empfänger, Betreff, Nachrichteninhalt, technische E-Mail-Metadaten | Europäische Union / ggf. Drittländer | eingesetzt |
| DNS- und Domainanbieter | Domainverwaltung, DNS-Auflösung, technische Erreichbarkeit der Website und Anwendung | technische DNS- und Domainverwaltungsdaten | je nach Anbieter | noch festzulegen |
| {{ANALYTICS_PROVIDER}} | Datenschutzfreundliche Nutzungsanalyse ohne Cookies | aggregierte Nutzungsdaten, Referrer, Seitenaufrufe, Gerät, Browser, Land des Zugriffs | gemäß Angaben des Anbieters / ggf. Drittländer | eingesetzt |
| {{BACKUP_PROVIDER}} | Speicherung verschlüsselter Off-Server-Backups | verschlüsselte Sicherungskopien der Anwendungsdatenbank und ggf. technischer Konfiguration | Europäische Union | eingesetzt |

## 2. Hosting-Anbieter

RetourenJournal wird auf Infrastruktur von {{HOSTING_PROVIDER}} betrieben.

Der Hosting-Anbieter stellt Server-, Netzwerk- und Rechenzentrumsleistungen bereit. Dabei können personenbezogene Daten verarbeitet werden, die in der Anwendung gespeichert oder technisch für den Betrieb erforderlich sind.

Die Verarbeitung erfolgt auf Servern innerhalb der Europäischen Union.

## 3. E-Mail-Infrastruktur

System-E-Mails der Anwendung, zum Beispiel zur E-Mail-Bestätigung oder Passwortwiederherstellung, werden über die E-Mail-Infrastruktur {{MAIL_SERVER_PROVIDER}} versendet.

Eingehende Nachrichten an Kontaktadressen können über den eigenen Mailserver angenommen, an {{INCOMING_MAIL_PROVIDER}} weitergeleitet und dort gespeichert werden.

Für {{INCOMING_MAIL_PROVIDER}} gelten die Datenschutz- und Nutzungsbedingungen des jeweiligen Anbieters.

## 4. DNS und Domain

Für die Erreichbarkeit der Website und Anwendung wird ein DNS- und Domainanbieter eingesetzt.

Der konkrete Anbieter wird vor produktiver Veröffentlichung benannt, soweit er personenbezogene Daten im Auftrag verarbeitet oder für die [Datenschutzerklärung](/legal/privacy) relevant ist.

## 5. {{ANALYTICS_PROVIDER}} Analytics

Für die Nutzungsanalyse wird {{ANALYTICS_PROVIDER}} eingesetzt.

Die Analysefunktion wird ohne Cookies eingesetzt und erstellt keine Werbeprofile.

{{ANALYTICS_PROVIDER}} ist ein externer Dienstleister für die statistische Auswertung von Seitenaufrufen und Nutzungsdaten. Die Verarbeitung erfolgt nur in dem Umfang, der für die Bereitstellung der Analysefunktion erforderlich ist.

## 6. {{BACKUP_PROVIDER}} Backups

Für Off-Server-Backups wird {{BACKUP_PROVIDER}} eingesetzt. Die Sicherungen werden vor der Übertragung verschlüsselt gespeichert und dienen ausschließlich der Wiederherstellung nach technischen Störungen oder Datenverlust.

Die Speicherung der Backup-Objekte erfolgt innerhalb der Europäischen Union.

## 7. Änderungen bei Unterauftragsverarbeitern

Der Auftragnehmer kann Unterauftragsverarbeiter ändern oder zusätzliche Unterauftragsverarbeiter einsetzen, soweit dies für Betrieb, Sicherheit, Wartung oder Weiterentwicklung erforderlich ist.

Der Auftraggeber wird über wesentliche Änderungen informiert, soweit dies im Rahmen des AVV erforderlich ist.

Der Auftraggeber kann aus wichtigem datenschutzrechtlichem Grund gegen den Einsatz eines neuen Unterauftragsverarbeiters widersprechen.

## 8. Drittlandübermittlungen

Eine Verarbeitung außerhalb der Europäischen Union oder des Europäischen Wirtschaftsraums findet nur statt, wenn hierfür eine geeignete Rechtsgrundlage besteht, zum Beispiel ein Angemessenheitsbeschluss, Standardvertragsklauseln oder ein anderer nach der DSGVO vorgesehener Mechanismus.

Soweit externe Dienstleister wie {{INCOMING_MAIL_PROVIDER}} oder {{ANALYTICS_PROVIDER}} Daten außerhalb der EU oder des EWR verarbeiten, erfolgt dies nur auf Grundlage geeigneter Garantien oder anderer nach der DSGVO vorgesehener Mechanismen.

