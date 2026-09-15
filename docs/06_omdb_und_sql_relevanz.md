# 06 OMDb, JSON, XML und SQL

## Externe Datenquelle

OMDb liefert Filmdaten in unterschiedlichen technischen Formaten. Im Projekt werden JSON und XML verwendet, weil beide Formate Bestandteil der Aufgabe sind und im Unterricht behandelt wurden.

## JSON-Antwort

Eine JSON-Antwort enthält Schlüssel wie:

```text
Title, Year, Runtime, Genre, Director, Actors, Plot, Language, Country, Poster, imdbID, Response
```

Diese Struktur kann in PHP mit `json_decode()` verarbeitet werden.

## XML-Antwort

Eine XML-Antwort von OMDb enthält ein `root`-Element und ein `movie`-Element. Die Filmdaten stehen dabei überwiegend als Attribute, zum Beispiel `title`, `year`, `runtime`, `genre`, `director`, `actors`, `plot`, `language`, `country`, `poster` und `imdbID`.

Diese Struktur wird mit SimpleXML gelesen und anschließend in dieselbe interne Form gebracht wie JSON.

## Normalisierung im Projekt

`OmdbClient` normalisiert JSON und XML auf gemeinsame interne Schlüssel. Dadurch kann das Model `Film` unabhängig davon arbeiten, ob die Daten ursprünglich aus JSON oder XML kamen.

## SQL-Zuordnung

Die Filmdaten werden nicht vollständig roh in eine einzige Tabelle geschrieben. Stattdessen werden zentrale Informationen in passende Tabellen verteilt:

- Filmdaten in `films`.
- Actors in `actors` und `films_actors`.
- Genres in `genres` und `films_genres`.
- Regisseur, Sprache und Land in eigene Nachschlagetabellen.
- Rohdateien in `api_files`.

## Bewusster Umgang mit Originalwerten

Einige OMDb-Werte bleiben als kompletter Text erhalten. Das betrifft insbesondere Sprache, Land und Regisseur. Dadurch werden externe Daten nicht künstlich zerlegt, wenn diese Zerlegung für die Anwendung keinen praktischen Vorteil hätte.

## Passwort-Hashing

Benutzerpasswörter werden mit `password_hash(..., PASSWORD_DEFAULT)` gespeichert. Beim Login wird `password_verify()` verwendet. Damit speichert das Projekt keine Klartextpasswörter.
