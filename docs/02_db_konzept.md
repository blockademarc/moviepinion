# 02 Datenbankkonzept

## Datenbankname

`gruppe2`

## Ziel des Datenbankmodells

Das Datenbankmodell bildet eine lokale Filmsammlung ab, die aus OMDb-Daten befüllt wird. Es speichert nicht nur die sichtbaren Filminformationen, sondern auch Benutzer, Kommentare, interne Nachrichten und die zugehörigen JSON-/XML-Rohdateien.

## Benennungsregeln

- Tabellen werden klein und im Plural benannt, zum Beispiel `films` oder `users`.
- Klassen werden groß und im Singular benannt, zum Beispiel `Film` oder `User`.
- Jede Tabelle besitzt `id` als Primärschlüssel.
- Fremdschlüssel verwenden den Tabellennamen mit `_id`, zum Beispiel `films_id` oder `users_id`.
- Echte m:n-Beziehungen werden über Zwischentabellen modelliert.

## Zentrale Tabellen

Die Tabelle `films` ist der Kern des Portals. Dort werden IMDb-ID, Titel, Jahr, Laufzeit, Plot, Poster und die Verweise auf Regisseur, Sprache und Land gespeichert.

```text
films = {id, imdb_id, title, year, runtime, directors_id, languages_id, countries_id, plot, poster, created_at}
```

`imdb_id` ist eindeutig. Dadurch kann derselbe Film beim erneuten Import erkannt werden und wird nicht doppelt in die lokale Filmsammlung übernommen.

## Normalisierung

Actors und Genres werden als echte m:n-Beziehungen geführt:

- Ein Film kann mehrere Schauspieler haben.
- Ein Schauspieler kann in mehreren Filmen vorkommen.
- Ein Film kann mehrere Genres haben.
- Ein Genre kann bei mehreren Filmen vorkommen.

Dafür werden diese Tabellen verwendet:

```text
actors = {id, actor}
films_actors = {id, films_id, actors_id}
genres = {id, genre}
films_genres = {id, films_id, genres_id}
```

## Bewusste Vereinfachung bei OMDb-Textwerten

`directors`, `languages` und `countries` werden als vollständige OMDb-Angaben gespeichert. Diese Entscheidung ist fachlich begründet, weil OMDb Werte wie `English, Italian, Latin` oder zusammengesetzte Länderangaben als fertigen Text liefert. Eine weitere Zerlegung wäre möglich, hätte für die geforderte Anwendung aber keinen zusätzlichen Nutzen gebracht.

```text
directors = {id, director}
languages = {id, language}
countries = {id, country}
```

## Benutzer, Kommentare und Nachrichten

Registrierte Benutzer können Kommentare zu Filmen schreiben und Nachrichten an den Administrator senden. Kommentare gehören immer zu einem Benutzer und zu einem Film. Nachrichten können vom Administrator beantwortet werden.

```text
users = {id, alias, email, passwort, rolle, created_at}
comments = {id, users_id, films_id, comment, created_at}
messages = {id, users_id, name, email, subject, message, answer, status, created_at, answered_at}
```

Beim Löschen eines Benutzers werden dessen Kommentare mitgelöscht. Dadurch bleiben keine verwaisten Kommentardatensätze zurück.

## Rohdateien

Die Tabelle `api_files` verbindet lokal gespeicherte JSON-/XML-Dateien mit dem jeweiligen Film. Pro Film und Format wird nur ein Datensatz geführt.

```text
api_files = {id, users_id, films_id, imdb_id, format, file_path, created_at}
```
