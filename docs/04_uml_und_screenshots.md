# 04 UML-Diagramme und Screenshots

## Zweck dieser Ergänzung

Diese Datei ergänzt die HTML-Dokumentation um die visuellen Nachweise des Projekts. Die UML-Diagramme zeigen den inneren Aufbau der Anwendung, während die Screenshots die fertige Oberfläche und zentrale Abläufe sichtbar machen.

## UML-Datenbankdiagramm

Das UML-Datenbankdiagramm stellt das relationale Datenmodell des Filmportals dar. Im Mittelpunkt steht die Tabelle `films`, in der die lokal übernommenen Kerndaten eines Films gespeichert werden. Wiederholbare Angaben wie Genres und Schauspieler werden über eigene Tabellen und Verbindungstabellen abgebildet. Benutzer, Kommentare, interne Nachrichten und gespeicherte API-Dateien bilden eigene fachliche Bereiche.

Dadurch wird nachvollziehbar, welche Daten aus OMDb stammen, welche Daten innerhalb der Anwendung entstehen und wie die Tabellen über Primär- und Fremdschlüssel zusammenhängen.

## UML-Klassendiagramm

Das UML-Klassendiagramm zeigt die objektorientierte Struktur der Anwendung. Es dokumentiert die gemeinsame Datenbankbasis, die gemeinsame Modelbasis, die eingesetzten Interfaces, den Trait zur Bereinigung von OMDb-Nachschlagewerten und die konkreten Models.

Damit wird sichtbar, dass die Anwendung nicht aus isolierten Einzelskripten besteht, sondern über wiederverwendbare Klassen, klare Zuständigkeiten und gemeinsame Datenbankmethoden aufgebaut ist.

## Screenshots

Die Screenshots dokumentieren die Benutzeroberfläche des fertigen Portals. Sie zeigen die öffentliche Galerie, die lokale Suche, den Kopfbereich, Navigation, Kontakt, Informationsseiten, den Benutzerbereich sowie zentrale Backend-Funktionen.

Im Backend werden unter anderem Login, Statistiken, Benutzerverwaltung, Kommentarverwaltung, Anfragen, Export, Backup und die Dokumentationsseite gezeigt. Dadurch ergänzen die Screenshots den Quellcode um den sichtbaren Nachweis, dass die Funktionen über die Oberfläche erreichbar sind.

## Ablage der Dateien

Die WEBP-Dateien der UML-Diagramme liegen im Admin-Bildordner. Die bearbeitbaren Dia-Dateien liegen zusätzlich im Ordner `docs/`, damit die Diagramme bei Bedarf nachvollziehbar geöffnet und verändert werden können.

Die Screenshots liegen im Ordner `admin/bilder/screenshots/` und werden auf der Dokumentationsseite direkt eingebunden.
