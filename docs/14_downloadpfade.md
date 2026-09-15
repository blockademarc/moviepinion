# 14 Downloadpfade

## Problemstellung

Gespeicherte OMDb-Rohdateien sollen herunterladbar sein, ohne unsichere oder betriebssystemabhängige Dateipfade in der Datenbank zu speichern.

## Projektinterne Pfade

In `api_files.file_path` wird ein projektinterner Pfad gespeichert, zum Beispiel:

```text
daten/json/filmname_tt1234567.json
```

Der Dateiname enthält den bereinigten Filmtitel und die IMDb-ID. Dadurch erhalten auch gleichnamige Filme unterschiedliche Rohdateien. Ohne Titel wird die IMDb-ID als Dateiname verwendet.

Es wird kein absoluter Windows- oder Linux-Pfad gespeichert. Dadurch bleibt die Datenbank zwischen unterschiedlichen lokalen Umgebungen besser übertragbar.

## Sicherheitsprüfung

Beim Download wird der gespeicherte Pfad mit `realpath()` geprüft. Eine Datei wird nur ausgeliefert, wenn sie wirklich existiert und sich innerhalb des erlaubten Datenordners befindet.

## Nutzen

Diese Lösung verbindet Benutzerfreundlichkeit und Sicherheit: Benutzer können vorhandene Rohdateien herunterladen, aber der Download kann nicht beliebige Dateien außerhalb des Projekts ausliefern.
