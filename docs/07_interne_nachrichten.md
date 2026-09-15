# 07 Interne Nachrichten

## Zweck der Funktion

Registrierte Benutzer können über das Kontaktformular eine Nachricht an den Administrator senden. Die Funktion erfüllt damit die Anforderung, dass angemeldete Benutzer dem Administrator eine Nachricht schicken können.

## Erweiterung der Grundanforderung

Die Nachricht wird nicht nur gespeichert, sondern kann im Backend beantwortet werden. Dadurch entsteht ein einfacher interner Kommunikationsweg innerhalb der Anwendung, ohne dass ein zusätzliches externes Mail-System benötigt wird.

## Datenbank

Die Nachrichten werden in der Tabelle `messages` gespeichert.

Wichtige Felder sind:

- `users_id` für den angemeldeten Benutzer.
- `name`, `email`, `subject` und `message` für die Anfrage.
- `answer` für die Antwort des Administrators.
- `status` für den Bearbeitungsstand.
- `created_at` und `answered_at` für die zeitliche Nachvollziehbarkeit.

## Ablauf

1. Ein angemeldeter Benutzer öffnet das Kontaktformular.
2. Die Eingaben werden validiert und in `messages` gespeichert.
3. Der Administrator sieht die Anfrage im Backend unter `Anfragen`.
4. Der Administrator kann eine Antwort speichern.
5. Der Status wird auf `beantwortet` gesetzt.
6. Der Benutzer sieht Anfrage und Antwort im Bereich `Meine Anfragen`.

## Beteiligte Dateien

- Frontend-Controller: `index.php`
- Backend-Controller: `admin/index.php`
- Model: `Message`
- Frontend-View: `views/meineNachrichten.php`
- Backend-View: `admin/views/messages.php`

## Bewertung der Lösung

Die Lösung ist bewusst einfach und projektbezogen gehalten. Sie nutzt die vorhandene Datenbankstruktur, bleibt im geschützten Benutzerbereich und ist für die Abgabe gut nachvollziehbar.
