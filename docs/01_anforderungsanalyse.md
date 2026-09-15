# 01 Anforderungsanalyse

## Projekt

Film-Portal-Applikation, Gruppe 2.

## Gruppenarbeit

Die Umsetzung erfolgte durch Alexander Claußen und Martin Frackowiak als abgestimmte Gruppenarbeit. Aufgabenverständnis, Datenmodell, Funktionsumfang und technische Entscheidungen wurden regelmäßig gemeinsam geprüft und am Projektstand abgeglichen.

## Ziel

Die Anwendung holt Filmdaten aus der OMDb API, speichert die Originalantworten als JSON- und XML-Dateien und übernimmt die wichtigsten Filminformationen in eine lokale MySQL-Datenbank. Danach arbeiten Galerie, Suche, Einzelansicht, Kommentare, Statistiken und Export mit den lokal gespeicherten Daten.

## Sprache

Die Anwendung ist auf Deutsch geschrieben. Externe OMDb-Daten bleiben im Original, weil sie aus einer englischsprachigen Quelle stammen und nicht verfälscht werden sollen. Dadurch bleiben JSON, XML, Datenbank und sichtbare Filminformationen fachlich konsistent.

## Bereiche

- Öffentlich: Archiv, Einzelansicht, lokale Suche, Kontakt, Impressum, About, AGB.
- Eingeloggt: Film hinzufügen, JSON/XML-Dateien herunterladen, Kommentare schreiben, Nachrichten an den Admin senden.
- Admin: Statistiken, Benutzer, Kommentare, Anfragen, Export, Backup und Dokumentation.

## Abgeleitete Hauptanforderungen

Aus der Aufgabenstellung ergeben sich drei zentrale Anforderungen: eine benutzerfreundliche Frontend-/Backend-Anwendung, eine nachvollziehbare OOP-/MVC-Struktur und ein sicherer Umgang mit Benutzereingaben, Datenbankzugriffen und Dateioperationen.

## Wichtige Hinweise aus dem Unterricht

- Analyse vor der Implementierung.
- Normalisierte Datenbankstruktur.
- Klassen im Singular, Tabellen im Plural.
- Jede zentrale Tabelle besitzt ein zugehöriges Model.
- Der Controller steuert die Aktionen und lädt die passenden Views.
- Frontend und Backend besitzen getrennte Controller.
- JSON und XML sind Bestandteil der Projektanforderung.
- OMDb-Antworten werden als Dateien gespeichert und bleiben dadurch prüfbar.
