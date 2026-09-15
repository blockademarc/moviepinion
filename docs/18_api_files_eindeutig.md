# 18 Eindeutige Rohdateien

## Zweck der Tabelle api_files

Die Tabelle `api_files` dokumentiert, welche JSON- oder XML-Rohdateien zu einem lokal gespeicherten Film vorhanden sind. Diese Dateien gehören zum jeweiligen Film und können später heruntergeladen werden.

## Eindeutigkeitsregel

Pro Film und Format wird nur ein Datensatz gespeichert:

- ein JSON-Eintrag pro Film
- ein XML-Eintrag pro Film

Dadurch wird `api_files` nicht zu einer unübersichtlichen Abruf-Historie.

## Verhalten bei erneutem Import

Die Rohdateinamen enthalten zusätzlich zum bereinigten Filmtitel die IMDb-ID. Gleichnamige Filme werden dadurch in unterschiedlichen Dateien gespeichert.

Wenn derselbe Film erneut im gleichen Format geholt wird, kann die Datei im Ordner `daten/` aktualisiert werden. In der Datenbank bleibt derselbe Film-/Format-Bezug erhalten; `file_path` wird auf die gerade gespeicherte Datei aktualisiert. Bereits vorhandene Dateien werden nicht automatisch umbenannt.

## Datenbanksicherung der Regel

Ein eindeutiger Schlüssel auf `films_id` und `format` stellt sicher, dass diese Regel auch auf Datenbankebene eingehalten wird.

## Nutzen

Die Galerie, die Statistik und die Downloadfunktion bleiben übersichtlich, weil zu jedem Film klar erkennbar ist, welche Rohformate vorhanden sind.
