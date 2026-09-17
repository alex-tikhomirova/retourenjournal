# Technische und organisatorische Maßnahmen (TOMs)

Stand: [Datum eintragen]
Version: 0.1

Dieses Dokument beschreibt die technischen und organisatorischen Maßnahmen im Sinne des Art. 32 DSGVO für den Betrieb von RetourenJournal.

Die Maßnahmen können an technische, organisatorische oder rechtliche Entwicklungen angepasst werden, sofern das Schutzniveau dadurch nicht wesentlich unterschritten wird.

## 1. Vertraulichkeit

### Zugriffskontrolle

Der Zugriff auf die Anwendung ist nur nach Authentifizierung möglich.

Passwörter werden nicht im Klartext gespeichert, sondern ausschließlich in gehashter Form.

Zugriffe auf Server, Datenbank und produktive Infrastruktur werden auf berechtigte Personen beschränkt.

Administrative Zugänge sind auf das notwendige Maß zu begrenzen und gegen unbefugten Zugriff zu schützen.

### Mandantentrennung

RetourenJournal ist mandantenfähig. Daten werden einer Organisation zugeordnet.

Der Zugriff auf Retouren, Kunden, Sendungen, Erstattungen und Verlaufsdaten erfolgt organisationsbezogen.

Die Anwendung stellt sicher, dass Nutzer nur auf Daten der jeweils berechtigten Organisation zugreifen können.

### Berechtigungskonzept

Nutzer werden einer Organisation zugeordnet.

Soweit Rollen oder Eigentümerstatus verwendet werden, dienen diese zur Steuerung organisatorischer Berechtigungen innerhalb der Anwendung.

Berechtigungen werden nach dem Prinzip der Erforderlichkeit vergeben.

### Vertraulichkeitspflichten

Personen mit Zugriff auf personenbezogene Daten werden zur Vertraulichkeit verpflichtet oder unterliegen einer angemessenen gesetzlichen Verschwiegenheitspflicht.

## 2. Integrität

### Schutz vor unbefugter Veränderung

Änderungen an relevanten Daten werden durch die Anwendung nur authentifizierten und berechtigten Nutzern ermöglicht.

Wichtige Vorgänge, insbesondere Statusänderungen, Entscheidungen, Sendungen, Erstattungen und Änderungen an Retouren, können in einer Ereignishistorie dokumentiert werden.

### Validierung und Plausibilitätsprüfungen

Eingaben werden serverseitig validiert.

Fehlerhafte oder unvollständige Eingaben können abgelehnt oder mit Fehlermeldungen beantwortet werden.

### Protokollierung

Technische Logs und anwendungsbezogene Ereignisse können zur Fehleranalyse, Sicherheit und Nachvollziehbarkeit verarbeitet werden.

Die Protokollierung erfolgt zweckgebunden und wird auf das erforderliche Maß beschränkt.

## 3. Verfügbarkeit und Belastbarkeit

### Hosting

Die Anwendung wird auf Servern innerhalb der Europäischen Union betrieben.

Der Hosting-Anbieter stellt die grundlegende Rechenzentrums-, Netzwerk- und Serverinfrastruktur bereit.

### Datensicherung

Soweit für die jeweilige Infrastruktur eingerichtet, werden Backups oder Snapshots zur Wiederherstellung nach technischen Störungen erstellt.

Backup- und Wiederherstellungsprozesse werden dem Reifegrad des Dienstes entsprechend weiterentwickelt.

### Wartung und Updates

Server- und Anwendungskomponenten werden angemessen gepflegt und aktualisiert.

Sicherheitsrelevante Updates werden nach Möglichkeit zeitnah eingespielt.

### Fehlerbehandlung

Technische Fehler können protokolliert und analysiert werden, um Stabilität und Verfügbarkeit der Anwendung zu verbessern.

## 4. Belastbarkeit der Systeme

Die Anwendung und Infrastruktur werden so betrieben, dass sie für den vorgesehenen Nutzungsumfang angemessen belastbar sind.

Bei erkennbaren Sicherheitsrisiken oder Störungen können technische Maßnahmen ergriffen werden, um Missbrauch, Überlastung oder unbefugten Zugriff zu verhindern.

## 5. Verschlüsselung und Übertragungssicherheit

Die Übertragung zwischen Browser und Anwendung erfolgt über HTTPS, soweit die Anwendung produktiv betrieben wird.

Administrativer Zugriff auf Server und Infrastruktur erfolgt über geschützte Verbindungen.

Passwörter werden ausschließlich in gehashter Form gespeichert.

Eine Verschlüsselung einzelner Datenbankfelder ist in der aktuellen Version nicht standardmäßig vorgesehen.

## 6. Wiederherstellbarkeit

Soweit Backups oder Snapshots eingerichtet sind, dienen diese der Wiederherstellung der Anwendung und Daten nach technischen Störungen oder Datenverlust.

Die Wiederherstellung einzelner Datensätze kann nicht garantiert werden, sofern dies nicht ausdrücklich vereinbart ist.

## 7. Verfahren zur regelmäßigen Überprüfung

Die technischen und organisatorischen Maßnahmen werden bei wesentlichen Änderungen der Anwendung, Infrastruktur oder rechtlichen Anforderungen überprüft.

Erkannte Schwachstellen oder Risiken werden nach Dringlichkeit bewertet und bearbeitet.

## 8. Datenschutz durch Technikgestaltung und datenschutzfreundliche Voreinstellungen

RetourenJournal wird nach dem Prinzip der Datenminimierung entwickelt.

Pflichtfelder werden auf das für die jeweilige Funktion erforderliche Maß beschränkt.

Daten werden organisationsbezogen getrennt.

Funktionen werden so gestaltet, dass nicht mehr personenbezogene Daten verarbeitet werden müssen, als für den jeweiligen Zweck erforderlich ist.

## 9. Löschung und Datenminimierung

Konten, Organisationen und zugehörige Daten können nach Maßgabe der Anwendung und der rechtlichen Rahmenbedingungen gelöscht werden.

Bei Löschung einer Organisation können zugehörige Retouren, Kunden, Artikel, Sendungen, Erstattungen und Verlaufsdaten gelöscht werden.

Technische Logs und Backups können Daten noch für einen begrenzten Zeitraum enthalten, bis sie regulär gelöscht oder überschrieben werden.

## 10. Trennung von Entwicklungs- und Produktivumgebung

Soweit getrennte Umgebungen betrieben werden, werden Entwicklungs-, Test- und Produktivumgebungen organisatorisch oder technisch voneinander getrennt.

Produktive personenbezogene Daten sollen nicht ohne Notwendigkeit in Entwicklungs- oder Testumgebungen verwendet werden.

## 11. Umgang mit Datenschutzverletzungen

Bei Verdacht auf eine Verletzung des Schutzes personenbezogener Daten werden Ursache, Umfang und mögliche Folgen geprüft.

Soweit Daten betroffen sind, die im Auftrag einer Organisation verarbeitet werden, wird die betroffene Organisation nach Maßgabe des AVV informiert.

Erforderliche Maßnahmen zur Begrenzung und Behebung des Vorfalls werden ergriffen.

## 12. Unterauftragsverarbeiter

Unterauftragsverarbeiter werden nur eingesetzt, soweit dies für den Betrieb, die Sicherheit oder die Bereitstellung der Anwendung erforderlich ist.

Die jeweils eingesetzten Unterauftragsverarbeiter werden im Dokument [Unterauftragsverarbeiter](/legal/subprocessors) aufgeführt.

