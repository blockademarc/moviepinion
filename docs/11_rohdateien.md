# 11 Rohdateien

## Zweck der Rohdateien

Beim Import eines Films wird die Originalantwort von OMDb zusätzlich als JSON- oder XML-Datei gespeichert. Diese Dateien dokumentieren, welche externen Daten zum Zeitpunkt des Imports geliefert wurden.

## Download durch angemeldete Benutzer

Angemeldete Benutzer können vorhandene Rohdateien zu einem lokal gespeicherten Film herunterladen. Die Funktion erzeugt keinen neuen Import, sondern stellt nur bereits gespeicherte Dateien bereit.

## Datenfluss

Der Ablauf bleibt dadurch klar:

```text
OMDb -> Rohantwort speichern -> Filmdaten in MySQL übernehmen -> Rohdatei später herunterladen
```

## Abgrenzung zum Export

Rohdateien sind die ursprünglichen OMDb-Antworten. Der Export im Backend erzeugt dagegen neue Dateien aus den lokal gespeicherten Daten. Beide Funktionen erfüllen unterschiedliche Aufgaben und werden deshalb getrennt behandelt.
