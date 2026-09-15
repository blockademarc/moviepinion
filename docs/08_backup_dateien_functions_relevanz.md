# 08 Backup, Export und PHP-Funktionen

## Zweck dieser Ergänzung

Diese Datei dokumentiert die technischen Zusatzfunktionen, die über die reine Filmanzeige hinausgehen: Datenbankbackup, Exportdateien und der Bezug zu im Unterricht behandelten PHP-Funktionen.

## Backup

Das Backup wird im Backend angestoßen und nutzt `mysqldump`. Damit wird ein externer Datenbankbefehl aus der PHP-Anwendung heraus verwendet, wie es im Unterricht als Thema behandelt wurde.

Beispielhafter Befehl:

```cmd
mysqldump -B --add-drop-database -uroot gruppe2 > backup_gruppe2_DATUM.sql
```

`-B` nimmt die Datenbank selbst in den Dump auf. `--add-drop-database` ergänzt um eine Drop-Anweisung, damit eine Wiederherstellung auf einem Testsystem einfacher möglich ist.

## Ermittlung von mysqldump

Die Funktion `findeMysqldump()` sucht von der Projektstruktur aus nach `mysql/bin/mysqldump.exe`. Wird keine XAMPP-Installation gefunden, wird als Rückfall der normale Befehl `mysqldump` verwendet. Dadurch ist die Lösung nicht starr an einen einzigen lokalen Pfad gebunden.

## Export

Der Adminbereich kann lokal gespeicherte Filmdaten in drei Formaten ausgeben:

- JSON
- XML
- CSV

Damit wird die geforderte Ausgabe in drei Datenformaten erfüllt. Exportiert werden lesbare Filmdaten und nicht nur technische IDs.

## PHP-Funktionen im Projektkontext

Im Projekt werden PHP-Funktionen dort eingesetzt, wo sie die Anwendung konkret unterstützen:

- `password_hash()` und `password_verify()` für sichere Passwörter.
- `realpath()` für geprüfte Dateidownloads aus dem erlaubten Datenordner.
- `file_put_contents()` zum Speichern von OMDb-Rohdateien und Exporten.
- `json_decode()` und `simplexml_load_string()` für die Verarbeitung von JSON und XML.
- `SimpleXMLElement` zur Erzeugung des XML-Exports.
- `fputcsv()` zur Erzeugung des CSV-Exports.
- `method_exists()` zur kontrollierten Datenübernahme in Models.
- `exec()` und `escapeshellarg()` für die gesicherte Ausführung des Backup-Befehls.
- `htmlspecialchars()` und projektinterne Ausgabefunktionen zur sicheren Browserausgabe.

Weitere behandelte PHP-Themen wie Closures, Arrow Functions oder Variadic Functions wurden nicht künstlich eingebaut, weil sie für die konkrete Projektlogik keinen klaren Nutzen hatten.
