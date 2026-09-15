# 09 Statistiken

## Zweck der Statistikseite

Die Statistikseite im Backend gibt dem Administrator einen schnellen Überblick über Systemdaten und fachliche Filmdaten. Sie erfüllt außerdem die Aufgabenanforderung `[Statistiken]`.

## Systemstatistik

Die Systemstatistik zählt organisatorische Daten der Anwendung:

- registrierte Benutzer
- Kommentare
- Kontaktanfragen
- offene Kontaktanfragen
- gespeicherte JSON-Dateien
- gespeicherte XML-Dateien

Dadurch erkennt der Administrator, wie stark die wichtigsten Systembereiche genutzt werden.

## Filmstatistik

Die Filmstatistik wertet die lokal gespeicherte Filmsammlung aus. Sie zeigt zum Beispiel:

- Anzahl lokaler Filme
- Anzahl gespeicherter Schauspieler
- Anzahl gespeicherter Genres
- Anzahl gespeicherter Regisseure
- häufigstes Genre
- häufigster Schauspieler
- häufigster Regisseur
- häufigstes Jahr
- häufigste Sprach- und Länderangaben

## SQL-Bezug

Für diese Auswertung werden SQL-Funktionen und Gruppierungen eingesetzt, besonders `COUNT()`, `GROUP BY` und sortierte Abfragen. Dadurch ist die Statistik nicht nur eine Oberfläche, sondern auch ein nachvollziehbarer Einsatz von SQL-Auswertung im Projekt.

## Nutzen für die Dokumentation

Die Statistikseite zeigt, dass die gespeicherten Daten nicht nur angezeigt, sondern auch ausgewertet werden können. Sie verbindet damit Datenbankmodell, lokale Filmdaten und Adminbereich.
