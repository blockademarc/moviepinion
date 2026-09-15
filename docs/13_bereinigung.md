# 13 Fachliche und technische Bereinigung

## Zweck dieser Ergänzung

Diese Datei dokumentiert Entscheidungen, mit denen das Projekt vor der Abgabe fachlich klarer und technisch besser erklärbar gemacht wurde.

## OmdbClient

`OmdbClient` liegt im Ordner `includes/` und nicht in `models/`. Die Klasse ruft OMDb auf, verarbeitet JSON/XML und speichert Rohantworten als Datei. Sie ist aber kein Model zu einer Datenbanktabelle. Deshalb erbt sie nicht von `aModel` und muss keine Datenbankmethoden wie `insert()`, `select()` oder `selectAll()` künstlich erfüllen.

## Ratings

Eine separate Bewertungstabelle wurde nicht verwendet. OMDb liefert zwar Rating-Angaben, die geforderte Anwendung arbeitet jedoch mit Titel, Jahr, IMDb-ID, Regisseur, Sprache, Land, Laufzeit, Actors, Genres, Plot, Poster und Kommentaren. Eine zusätzliche Rating-Struktur hätte das Modell vergrößert, ohne eine konkrete Projektanforderung zu erfüllen.

## Fremdschlüssel

Fremdschlüsselspalten wurden einheitlich benannt, zum Beispiel `users_id`, `films_id`, `actors_id` und `genres_id`. Dadurch sind JOIN-Abfragen und Beziehungen im Datenbankmodell leichter nachvollziehbar.

## Exportdaten

Der Export gibt lesbare Filmdaten aus. Es werden nicht nur technische IDs exportiert, sondern auch Regisseur, Sprache, Land, Actors und Genres. Damit entspricht der Export dem fachlichen Inhalt der Anwendung.

## Migrationsskripte

Die Dateien im Ordner `migrations/` dokumentieren Entwicklungsschritte. Für eine frische Installation ist `sql/create_database.sql` maßgeblich.
