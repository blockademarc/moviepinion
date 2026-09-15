# 15 Interfaces und Validierung

## Interfaces

Die Anwendung verwendet zwei Interfaces:

- `iDatenbank` für `insert()`, `select()` und `selectAll()`.
- `iLoeschbar` für `delete()`.

Diese Aufteilung ist bewusst gewählt. Nicht jede Klasse darf oder muss löschen können. Deshalb wird `delete()` nur dort verlangt, wo es fachlich zur Aufgabe gehört.

## Kein vollständiges CRUD

Ein allgemeines `update()` wurde nicht in das Interface aufgenommen. Die importierten OMDb-Filmdaten werden nach dem Import nicht redaktionell bearbeitet. Dadurch bleibt das Interface näher an der tatsächlichen Anwendung und erzeugt keine unnötigen Pflichtmethoden.

## Registrierung

Die Registrierung prüft:

- Alias ist vorhanden.
- E-Mail-Adresse ist formal gültig.
- Passwort hat mindestens 6 Zeichen.
- Passwort und Passwortwiederholung stimmen überein.
- Alias und E-Mail-Adresse sind eindeutig.

Das Passwort wird vor dem Speichern gehasht.

## OMDb-Datenbereinigung

OMDb-Werte werden vor dem Speichern getrimmt. Leere Werte und `N/A` werden nicht als eigenständige Nachschlagewerte gespeichert. Dadurch entstehen keine falschen Einträge in Tabellen wie `actors`, `genres`, `directors`, `languages` oder `countries`.

## Bedeutung für die Sicherheit

Die Validierung verhindert typische Fehler wie leere Pflichtfelder, doppelte Benutzer, ungültige E-Mail-Adressen und unbrauchbare OMDb-Nachschlagewerte. Sie ergänzt damit die Prepared Statements und die gesicherte Ausgabe im Frontend.
