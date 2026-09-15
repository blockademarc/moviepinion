# 16 Interfaces und Poster-Platzhalter

## Interfaces im finalen Stand

Die Interface-Struktur bleibt bewusst klein:

- `iDatenbank` beschreibt das Anlegen und Lesen von Datensätzen.
- `iLoeschbar` beschreibt das Löschen nur für passende Klassen.

Damit passt die OOP-Struktur zur tatsächlichen Aufgabe. Filmdaten werden importiert und angezeigt, aber nicht über ein allgemeines Bearbeitungsformular verändert.

## Fehlende Poster

OMDb liefert nicht zu jedem Film ein nutzbares Poster. Ohne Ersatzbild würden Galerie, Einzelansicht und Suchergebnisse uneinheitlich wirken.

## Lösung im Projekt

Die Anwendung verwendet einen Poster-Platzhalter, wenn kein verwertbares Poster vorhanden ist. Dadurch bleibt das Layout stabil und der Benutzer erkennt, dass nur das Bild fehlt, nicht der gesamte Filmdatensatz.

## Bedeutung für UI/UX

Der Platzhalter unterstützt die Benutzerfreundlichkeit, weil die Oberfläche auch bei unvollständigen externen Daten ruhig und vollständig bleibt. Das ist wichtig, weil OMDb-Daten nicht in jedem Film gleich vollständig sind.
