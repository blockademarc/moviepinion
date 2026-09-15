# 17 Lokale Suche und Auto-Suggest

## Ziel der lokalen Suche

Die lokale Suche findet Filme, die bereits in der Datenbank `gruppe2` gespeichert sind. Sie fragt OMDb nicht ab und bleibt damit auf die eigene Filmsammlung beschränkt.

## Auto-Suggest

Während der Eingabe werden kurze Vorschläge aus lokalen Tabellen geladen. Die AJAX-Funktion verbessert die Benutzerführung, weil passende Werte bereits beim Tippen sichtbar werden.

## Suchlogik

Bei einfachen Werten wird bevorzugt am Anfang gesucht:

```text
LIKE 'Suchbegriff%'
```

Das betrifft zum Beispiel Titel, Jahr, IMDb-ID, Schauspieler und Genre.

Bei zusammengesetzten OMDb-Werten wird auch innerhalb des Textes gesucht:

```text
LIKE '%Suchbegriff%'
```

Das betrifft zum Beispiel Sprache, Land und Regisseur, weil dort komplette OMDb-Angaben gespeichert werden können.

## Normale Ergebnisliste

Nach dem Absenden des Suchformulars wird breiter gesucht. Benutzer müssen also nicht wissen, wie ein gespeicherter Titel oder Name exakt beginnt.

## Abgrenzung zu OMDb

Die lokale Suche zeigt vorhandene Filme. Neue Filme werden nur über die geschützte Funktion `Film hinzufügen` aus OMDb geholt. Dadurch bleibt der Datenfluss eindeutig und kontrolliert.
