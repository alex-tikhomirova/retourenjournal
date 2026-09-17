# Unterauftragsverarbeiter

Stand: [Datum eintragen]
Version: 0.1

Dieses Dokument listet die Unterauftragsverarbeiter und technischen Dienstleister auf, die im Rahmen des Betriebs von RetourenJournal eingesetzt werden oder eingesetzt werden können.

Unterauftragsverarbeiter werden nur eingesetzt, soweit dies für Bereitstellung, Betrieb, Sicherheit, Wartung oder Kommunikation erforderlich ist.

## 1. Aktuelle und geplante Dienstleister

| Dienstleister | Zweck | Verarbeitete Daten | Ort der Verarbeitung | Status |
| --- | --- | --- | --- | --- |
| {{HOSTING_PROVIDER}} | Hosting der Website, Anwendung, Datenbank und technischen Infrastruktur | Konto-, Organisations-, Retouren-, Kunden-, Versand-, Erstattungs-, Log- und technische Daten | Europäische Union | geplant / eingesetzt |
| Eigener Mailserver auf der Hosting-Infrastruktur | Versand von System-E-Mails und ggf. Annahme oder Weiterleitung eingehender Nachrichten | E-Mail-Adressen, Inhalte von System-E-Mails, Kontaktanfragen, technische Mail-Logs | Europäische Union | geplant |
| Externes E-Mail-Postfach | Empfang weitergeleiteter Kontaktanfragen | Absender, Empfänger, Betreff, Nachrichteninhalt, technische E-Mail-Metadaten | noch festzulegen | noch festzulegen |
| DNS- und Domainanbieter | Domainverwaltung, DNS-Auflösung, technische Erreichbarkeit der Website und Anwendung | technische DNS- und Domainverwaltungsdaten | je nach Anbieter | noch festzulegen |
| Plausible Analytics self-hosted | Datenschutzfreundliche Nutzungsanalyse ohne Cookies | aggregierte Nutzungsdaten, Referrer, Seitenaufrufe, Gerät, Browser, Land des Zugriffs | Europäische Union | geplant |

## 2. Hosting-Anbieter

RetourenJournal wird auf Infrastruktur von {{HOSTING_PROVIDER}} betrieben.

Der Hosting-Anbieter stellt Server-, Netzwerk- und Rechenzentrumsleistungen bereit. Dabei können personenbezogene Daten verarbeitet werden, die in der Anwendung gespeichert oder technisch für den Betrieb erforderlich sind.

Die Verarbeitung erfolgt auf Servern innerhalb der Europäischen Union.

## 3. E-Mail-Infrastruktur

System-E-Mails der Anwendung, zum Beispiel zur E-Mail-Bestätigung oder Passwortwiederherstellung, sollen über einen eigenen Mailserver auf der Hosting-Infrastruktur versendet werden.

Eingehende Nachrichten an Kontaktadressen können über den eigenen Mailserver angenommen und an ein externes Postfach weitergeleitet werden.

Soweit ein externer E-Mail-Anbieter eingesetzt wird, wird dieser vor produktiver Nutzung in diesem Dokument benannt.

## 4. DNS und Domain

Für die Erreichbarkeit der Website und Anwendung wird ein DNS- und Domainanbieter eingesetzt.

Der konkrete Anbieter wird vor produktiver Veröffentlichung benannt, soweit er personenbezogene Daten im Auftrag verarbeitet oder für die [Datenschutzerklärung](/legal/privacy) relevant ist.

## 5. Plausible Analytics

Für die Nutzungsanalyse ist eine selbst gehostete Instanz von Plausible Analytics geplant.

Plausible wird ohne Cookies eingesetzt und erstellt keine Werbeprofile.

Da die Instanz selbst auf der eigenen Hosting-Infrastruktur betrieben wird, ist Plausible selbst kein externer Unterauftragsverarbeiter, solange keine externe Plausible-Cloud genutzt wird.

## 6. Änderungen bei Unterauftragsverarbeitern

Der Auftragnehmer kann Unterauftragsverarbeiter ändern oder zusätzliche Unterauftragsverarbeiter einsetzen, soweit dies für Betrieb, Sicherheit, Wartung oder Weiterentwicklung erforderlich ist.

Der Auftraggeber wird über wesentliche Änderungen informiert, soweit dies im Rahmen des AVV erforderlich ist.

Der Auftraggeber kann aus wichtigem datenschutzrechtlichem Grund gegen den Einsatz eines neuen Unterauftragsverarbeiters widersprechen.

## 7. Drittlandübermittlungen

Eine Verarbeitung außerhalb der Europäischen Union oder des Europäischen Wirtschaftsraums findet nur statt, wenn hierfür eine geeignete Rechtsgrundlage besteht, zum Beispiel ein Angemessenheitsbeschluss, Standardvertragsklauseln oder ein anderer nach der DSGVO vorgesehener Mechanismus.

Soweit ein externer E-Mail-Anbieter mit Verarbeitung außerhalb der EU oder des EWR eingesetzt wird, wird dies vor produktiver Nutzung in diesem Dokument und in der [Datenschutzerklärung](/legal/privacy) berücksichtigt.

