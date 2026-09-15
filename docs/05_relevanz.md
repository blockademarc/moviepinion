# 05 Unterrichtsbezug und Projektumsetzung

## Zweck dieser Ergänzung

Diese Datei zeigt, welche Inhalte aus dem Unterricht im Filmportal fachlich sinnvoll umgesetzt wurden. Sie ergänzt die Hauptdokumentation, indem sie den Bezug zwischen Kursstoff und Projektentscheidung deutlich macht.

## Übernommene Projektgrundlagen

- Projektordner heißt `moviepinion`.
- Datenbank heißt `gruppe2`.
- Die Anwendung ist als Frontend-/Backend-Anwendung aufgebaut.
- Frontend und Backend verwenden eigene Controller.
- Navigation, Header, Footer und Meldungen werden als Partials beziehungsweise Templates eingebunden.
- Die Datenbank ist normalisiert und verwendet Fremdschlüssel.
- Klassen und Tabellen folgen einer nachvollziehbaren Benennung.

## Benutzer und Rollen

Die Registrierung ist bewusst einfach gehalten: Alias, E-Mail-Adresse und Passwort reichen für die Projektanforderung aus. Der Login erfolgt mit Alias und Passwort. Der Admin-Zugang ist getrennt und wird nicht über eine öffentliche Registrierung erzeugt.

## OMDb, JSON und XML

OMDb wird als externe Datenquelle verwendet. Filmdaten können in JSON und XML geholt werden. Die Originalantworten werden als Datei gespeichert und die wichtigsten Inhalte zusätzlich in die lokale Datenbank übernommen.

## Suche und Pagination

Die lokale Suche arbeitet mit SQL und `LIKE`. Das Auto-Suggest erfüllt die AJAX-Anforderung. Die Galerie verwendet Pagination mit vier Filmen pro Seite und knüpft damit an die im Unterricht gezeigte `LIMIT`-Logik an.

## OOP-Anforderungen

Die Anwendung enthält Interface, zweites Interface für Löschlogik, abstrakte Klassen, Trait, Magic Methods, Type-Hinting und Class-Funktionen. Diese Elemente wurden nicht künstlich ergänzt, sondern an Stellen eingesetzt, an denen sie die Projektstruktur tatsächlich unterstützen.

## Fehlerbehandlung

Fehler bei Datenbankverbindung, OMDb-Abruf, Validierung und Dateioperationen werden benutzerverständlich ausgegeben. Rohe PHP-, PDO- oder Servermeldungen werden nicht direkt an Benutzer weitergegeben.
