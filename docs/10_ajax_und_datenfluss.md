# 10 AJAX und Datenfluss

## Grundentscheidung

Die Anwendung trennt klar zwischen externer Datenquelle und lokaler Filmsammlung.

- OMDb ist die externe Quelle für neue Filmdaten.
- Die Datenbank `gruppe2` ist die lokale Filmsammlung.
- Galerie, Einzelansicht, Kommentare, Statistik, Export und lokale Suche arbeiten mit lokalen Daten.
- Beim Import wird die Originalantwort zusätzlich als JSON- oder XML-Datei gespeichert.

Dadurch bleibt der Datenfluss eindeutig und nachvollziehbar.

```text
OMDb -> Import -> JSON/XML-Datei + MySQL -> Galerie/Suche/Einzelansicht/Statistik/Export
```

## Lokale AJAX-Suche

Die öffentliche Suche arbeitet lokal. Sie fragt nicht OMDb ab. Während der Eingabe ruft JavaScript den Frontend-Controller auf und erhält Vorschläge als JSON zurück.

Ablauf:

1. Benutzer tippt einen Suchbegriff ein.
2. JavaScript ruft `index.php?action=autosuggestLokal&term=...` auf.
3. Der Controller leitet an das Model `Film` weiter.
4. `Film::autosuggestLokal()` sucht in lokalen Tabellen.
5. Die Vorschläge werden als JSON an JavaScript zurückgegeben.
6. Ein Klick auf einen Vorschlag übernimmt den Wert in das Suchfeld.

## Durchsuchte Bereiche

Die lokale Suche berücksichtigt:

- Filmtitel
- Jahr
- IMDb-ID
- Schauspieler
- Regisseur
- Genre
- Land
- Sprache

## Unterschied zwischen Vorschlag und Ergebnisliste

Auto-Suggest soll kurze und schnelle Vorschläge liefern. Deshalb sucht es bei einfachen Werten wie Titel, Jahr, IMDb-ID, Schauspieler und Genre bevorzugt am Anfang des Werts.

Bei zusammengesetzten OMDb-Angaben wie Sprache, Land und Regisseur wird auch innerhalb des Textes gesucht. Das ist notwendig, weil Werte wie `English, Italian, Latin` als vollständige Angabe gespeichert werden.

Die normale Ergebnisliste sucht breiter, damit Benutzer Treffer auch dann finden, wenn sie nicht den exakten Anfang eines gespeicherten Wertes kennen.

## OMDb-AJAX im geschützten Bereich

Die OMDb-Downloadmaske besitzt eine zusätzliche Vorschlagshilfe. Diese Funktion ist eine Komfortfunktion für angemeldete Benutzer und ersetzt nicht die lokale Suche. Der eigentliche Import passiert erst nach dem Absenden des Formulars.

## Bedeutung für die Projektlogik

Diese Trennung verhindert, dass die öffentliche Suche unkontrolliert externe Daten lädt. Neue Filme werden bewusst importiert; vorhandene Filme werden lokal gesucht und angezeigt.
