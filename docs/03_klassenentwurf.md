# 03 Klassenentwurf

## Ziel der OOP-Struktur

Die OOP-Struktur trennt Datenbankzugriff, gemeinsame Model-Logik und fachliche Einzelklassen. Dadurch bleibt nachvollziehbar, welche Klasse für welche Tabelle beziehungsweise Projektaufgabe zuständig ist.

## Gemeinsame Grundlage

- `iDatenbank` beschreibt die grundlegenden Datenbankmethoden `insert()`, `select()` und `selectAll()`.
- `iLoeschbar` beschreibt `delete()`, aber nur für Klassen, bei denen Löschen fachlich benötigt wird.
- `aDatenbank` stellt die PDO-Verbindung zur Datenbank `gruppe2` bereit.
- `aModel` bündelt gemeinsame Datenübernahme, Magic Methods und die Verbindung zur Datenbankbasis.
- `NachschlageTrait` bereinigt OMDb-Werte, bevor sie in Nachschlagetabellen gespeichert werden.

## Warum kein allgemeines Update-Interface verwendet wird

Die importierten Filmdaten stammen aus OMDb und werden im Projekt nicht manuell nachbearbeitet. Ein allgemeines `update()` im Interface würde deshalb eine CRUD-Struktur vortäuschen, die für das Filmportal nicht benötigt wird. Löschfunktionen sind dagegen dort vorhanden, wo die Aufgabe sie verlangt: bei Benutzern und Kommentaren.

## Models

Jede zentrale Tabelle besitzt ein eigenes Model:

- `Film` → `films`
- `User` → `users`
- `Comment` → `comments`
- `Message` → `messages`
- `ApiFile` → `api_files`
- `Genre` → `genres`
- `Actor` → `actors`
- `Director` → `directors`
- `Language` → `languages`
- `Country` → `countries`
- `FilmGenre` → `films_genres`
- `FilmActor` → `films_actors`

Für `Director`, `Language` und `Country` gibt es keine Zwischentabellen, weil diese Werte im Projekt als vollständige OMDb-Textangaben behandelt werden.

## Magic Methods

Die geforderten fünf Magic Methods werden in `aModel` eingesetzt:

- `__construct()` übernimmt Startdaten beim Erzeugen eines Objekts.
- `__destruct()` leert die interne Werteliste beim Beenden des Objekts.
- `__get()` erlaubt kontrollierten Zugriff auf gespeicherte Werte.
- `__set()` schreibt kontrolliert in die interne Werteliste.
- `__toString()` gibt gesetzte Modeldaten als einfache HTML-Liste aus.

Diese Magic Methods ersetzen keine fachlichen Modelklassen. Sie unterstützen nur die gemeinsame Datenhaltung und machen gesetzte Objektwerte nachvollziehbar.

## Type-Hinting, instanceof und Class-Funktionen

Type-Hinting wird bei Parametern, Eigenschaften und Rückgabewerten eingesetzt, zum Beispiel bei IDs, Arrays, Strings und der PDO-Verbindung. Der `instanceof`-Operator wird in `aDatenbank` verwendet, um eine bereits vorhandene PDO-Verbindung zu erkennen und wiederzuverwenden.

Als Class-Funktion wird im Projekt `method_exists()` eingesetzt. In `aModel::setDaten()` wird damit geprüft, ob zu einem übergebenen Feld ein passender Setter vorhanden ist. Zusätzlich verwendet `Film` diese Prüfung beim Speichern von Nachschlagewerten und m:n-Verbindungen. Dadurch werden Daten kontrolliert übernommen, ohne Methoden aufzurufen, die in der jeweiligen Klasse nicht existieren.
