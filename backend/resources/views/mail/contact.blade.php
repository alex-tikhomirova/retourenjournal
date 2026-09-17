Neue Nachricht über das Kontaktformular von RetourenJournal

Name: {{ $contact['name'] }}
E-Mail: {{ $contact['email'] }}
Thema: {{ $topicLabel }}
Betreff: {{ !empty($contact['subject']) ? $contact['subject'] : '—' }}

Nachricht:
{{ $contact['message'] }}
