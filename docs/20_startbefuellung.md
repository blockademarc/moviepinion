# 20 Startbefüllung per setup.bat

## Zweck

Die Datei `setup.bat` richtet eine neue lokale Installation ein. Sie legt die Datenbank an und befüllt das Portal anschließend mit ersten Filmen.

Die Datenbank `gruppe2` darf dafür noch nicht existieren. Bei einer bereits vorhandenen Datenbank bricht der Schemaimport ab. Das Skript löscht keine bestehende Datenbank und ist nicht zum Aktualisieren einer vorhandenen Installation vorgesehen.

## Ablauf

`setup.bat` führt zwei Schritte aus:

1. `sql/create_database.sql` legt die neue Datenbank `gruppe2` einschließlich Tabellen und lokalem Admin-Testkonto an.
2. `sql/filme_befuellen.php` wird mit PHP gestartet und importiert Startfilme.

Der Schemaimport verwendet fest den lokalen MySQL-Benutzer `root` ohne Passwort und die Datenbank `gruppe2`. Dieser Schritt liest keine abweichenden Datenbankangaben aus `includes/config.php`. Das anschließende PHP-Skript verwendet die Konfigurationsdatei einschließlich des eigenen OMDb-API-Keys.

## Warum filme_befuellen.php im Ordner sql liegt

`filme_befuellen.php` gehört zur Datenbank-Startbefüllung. Es ist kein öffentliches Frontend-Skript und keine View, sondern ein Installations- beziehungsweise Kommandozeilenwerkzeug.

## Importlogik

Das Skript verwendet keine zweite Sonderlogik. Es lädt die normalen Projektdateien und nutzt dieselben Klassen wie die Anwendung:

- `OmdbClient`
- `Film`
- `ApiFile`
- Nachschlage- und Verbindungsklassen für Actors und Genres

Damit erfolgt die Verarbeitung wie beim Import über die Oberfläche:

- OMDb wird mit Titel und Jahr abgefragt.
- JSON und XML werden geholt.
- OMDb-Werte werden bereinigt.
- Filmdaten werden in MySQL gespeichert.
- Actors und Genres werden in m:n-Tabellen übernommen.
- Originalantworten werden in `daten/json/` und `daten/xml/` gespeichert.

## Nutzen

Nach dem Start sind Galerie, Suche, Einzelansichten, Statistiken und Downloadbereiche direkt prüfbar. Das erleichtert die lokale Kontrolle und sorgt für einen nachvollziehbaren Startzustand der Anwendung.
