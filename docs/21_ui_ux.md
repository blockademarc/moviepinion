# 21 UI / UX

## Ziel der Oberfläche

Die Anwendung soll auf den ersten Blick als Filmportal erkennbar sein. Die Gestaltung wurde deshalb nicht wie eine neutrale Verwaltungsmaske aufgebaut, sondern greift das Thema Film sichtbar auf.

## Visuelles Konzept

Das Design arbeitet mit dunklem Kopfbereich, warmen Akzentfarben, Kartenflächen, Posterflächen und filmbezogenen Grafikelementen. Dadurch entsteht ein Bezug zu Kino, Archiv und Filmsammlung.

## Benutzerführung

Die Navigation ist nach Benutzerrollen aufgebaut:

- Öffentliche Besucher sehen Archiv, Suche, Login, Registrierung und die rechtlichen beziehungsweise informativen Seiten.
- Angemeldete Benutzer erhalten zusätzlich Zugriff auf Film hinzufügen, Rohdateien, Kommentare und eigene Anfragen.
- Der Adminbereich bleibt getrennt und erscheint nicht in der öffentlichen Navigation.

Dadurch sieht jede Benutzergruppe nur die Funktionen, die für sie relevant sind.

## Meldungen

Fehler- und Erfolgsmeldungen wurden benutzerverständlich formuliert. Benutzer sollen nicht mit rohen PHP-, PDO- oder OMDb-Fehlern arbeiten müssen, sondern eine klare Rückmeldung bekommen.

## Umgang mit externen Daten

Die Oberfläche ist deutsch. Externe Filmdaten aus OMDb bleiben im Original. Das verhindert fachliche Verfälschungen und hält sichtbare Inhalte, gespeicherte Rohdateien und Datenbankwerte konsistent.

## Suche und Übersichtlichkeit

Die lokale Suche verwendet Auto-Suggest, damit vorhandene Filme schneller gefunden werden. Die Galerie zeigt nur eine begrenzte Anzahl Filme pro Seite. Dadurch bleibt die Oberfläche übersichtlich und die Pagination ist gut nachvollziehbar.

## Fehlende Poster

Wenn OMDb kein nutzbares Poster liefert, zeigt die Anwendung einen Platzhalter. Das Layout bleibt dadurch stabil und der Benutzer erkennt, dass nur das Bild fehlt.

## Backend

Das Backend ist sachlicher aufgebaut, bleibt gestalterisch aber mit dem Projekt verbunden. Es ist als Administrationsbereich erkennbar, wirkt wie eine dazugehörige Anwendung.
