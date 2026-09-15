# 12 Rohdateien in der lokalen Suche

## Zweck der Ergänzung

Die lokale Suche zeigt Filme aus der Datenbank `gruppe2`. Wenn zu einem Treffer weiterhin JSON- oder XML-Rohdateien vorhanden sind, können angemeldete Benutzer diese direkt aus dem Suchkontext herunterladen.

## Ablauf

1. Ein Film wird über OMDb importiert.
2. Die Originalantwort wird als JSON oder XML gespeichert.
3. Der Film wird in die lokale Datenbank übernommen.
4. Die lokale Suche findet den Film.
5. Bei vorhandenen Rohdateien erscheinen Downloadlinks für angemeldete Benutzer.

## Warum diese Lösung sinnvoll ist

Der Benutzer muss nicht in einen separaten Bereich wechseln, um vorhandene Rohdateien zu einem Film zu finden. Gleichzeitig bleibt die lokale Suche sauber: Sie sucht weiterhin nur in der eigenen Datenbank und startet keinen neuen OMDb-Abruf.

## Sicherheit

Der Download läuft nicht über frei zusammengesetzte Dateipfade. Es wird ein Datensatz aus `api_files` verwendet und anschließend geprüft, ob die Datei tatsächlich im erlaubten Datenordner liegt.
