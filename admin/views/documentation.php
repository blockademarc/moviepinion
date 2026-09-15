<?php
// Die Dokumentationsseite arbeitet mit einer festen Liste der ergänzenden Dokumentationsdateien.
// Dadurch bleibt die Reihenfolge der 21 Dateien nachvollziehbar und der iframe kann direkt auf die Markdown-Dateien zeigen.
$dokumentationsDateien = [
    ['datei' => '01_anforderungsanalyse.md', 'titel' => '01 Anforderungsanalyse', 'kurz' => 'Fasst Projektziel, Gruppenarbeit, Rollenbereiche und zentrale Anforderungen zusammen.'],
    ['datei' => '02_db_konzept.md', 'titel' => '02 Datenbankkonzept', 'kurz' => 'Erklärt Datenbankname, Benennungsregeln, Normalisierung und Beziehungen.'],
    ['datei' => '03_klassenentwurf.md', 'titel' => '03 Klassenentwurf', 'kurz' => 'Ordnet Interfaces, abstrakte Klassen, Trait, Models und Magic Methods ein.'],
    ['datei' => '04_uml_und_screenshots.md', 'titel' => '04 UML-Diagramme und Screenshots', 'kurz' => 'Erklärt Datenbankdiagramm, Klassendiagramm und Screenshot-Nachweise.'],
    ['datei' => '05_relevanz.md', 'titel' => '05 Unterrichtsbezug', 'kurz' => 'Zeigt, welche Unterrichtsinhalte fachlich sinnvoll in das Filmportal übernommen wurden.'],
    ['datei' => '06_omdb_und_sql_relevanz.md', 'titel' => '06 OMDb, JSON, XML und SQL', 'kurz' => 'Beschreibt OMDb-Formate, Normalisierung und Übernahme in die lokale Datenbank.'],
    ['datei' => '07_interne_nachrichten.md', 'titel' => '07 Interne Nachrichten', 'kurz' => 'Dokumentiert Benutzeranfragen, Admin-Antworten und den Nachrichtenfluss im System.'],
    ['datei' => '08_backup_dateien_functions_relevanz.md', 'titel' => '08 Backup, Export und Funktionen', 'kurz' => 'Erklärt Backup, Exportdateien und eingesetzte PHP-Funktionen im Projektkontext.'],
    ['datei' => '09_statistiken.md', 'titel' => '09 Statistiken', 'kurz' => 'Erklärt System- und Filmstatistiken im Backend.'],
    ['datei' => '10_ajax_und_datenfluss.md', 'titel' => '10 AJAX und Datenfluss', 'kurz' => 'Beschreibt die Trennung zwischen lokaler Suche, OMDb-Import und gespeicherten Rohdateien.'],
    ['datei' => '11_rohdateien.md', 'titel' => '11 Rohdateien', 'kurz' => 'Erklärt gespeicherte JSON-/XML-Originalantworten und deren Download.'],
    ['datei' => '12_rohdateien_in_lokaler_suche.md', 'titel' => '12 Rohdateien in der lokalen Suche', 'kurz' => 'Zeigt, wie vorhandene Rohdateien bei lokalen Suchtreffern angeboten werden.'],
    ['datei' => '13_bereinigung.md', 'titel' => '13 Fachliche und technische Bereinigung', 'kurz' => 'Begründet technische Entscheidungen zur Vereinfachung und besseren Erklärbarkeit.'],
    ['datei' => '14_downloadpfade.md', 'titel' => '14 Downloadpfade', 'kurz' => 'Begründet projektinterne Pfade und Sicherheitsprüfung für Datei-Downloads.'],
    ['datei' => '15_interfaces_validierung.md', 'titel' => '15 Interfaces und Validierung', 'kurz' => 'Begründet die reduzierte Interface-Struktur sowie Registrierung und OMDb-Bereinigung.'],
    ['datei' => '16_interfaces_poster.md', 'titel' => '16 Interfaces und Poster-Platzhalter', 'kurz' => 'Erklärt die finale Interface-Entscheidung und den Umgang mit fehlenden Postern.'],
    ['datei' => '17_lokale_suche.md', 'titel' => '17 Lokale Suche und Auto-Suggest', 'kurz' => 'Beschreibt Auto-Suggest, Ergebnisliste und Abgrenzung zu OMDb.'],
    ['datei' => '18_api_files_eindeutig.md', 'titel' => '18 Eindeutige Rohdateien', 'kurz' => 'Erklärt, warum pro Film und Format nur ein Rohdatei-Datensatz gespeichert wird.'],
    ['datei' => '19_finalbereinigung.md', 'titel' => '19 Finale technische Prüfung', 'kurz' => 'Fasst technische Prüf- und Bereinigungspunkte des finalen Projektstands zusammen.'],
    ['datei' => '20_startbefuellung.md', 'titel' => '20 Startbefüllung per setup.bat', 'kurz' => 'Beschreibt Datenbankimport und automatische Startdaten über setup.bat und filme_befuellen.php.'],
    ['datei' => '21_ui_ux.md', 'titel' => '21 UI / UX', 'kurz' => 'Beschreibt Gestaltung, Menüführung, Meldungen und Benutzerfreundlichkeit des Filmportals.'],
];
?>
<section class="seitenkopf">
    <h1>Dokumentation des Klausurprojekts Filmportal</h1>
    <p>Diese Dokumentation beschreibt die Umsetzung des PHPC-Klausurprojekts von der Aufgabenanalyse bis zur fertigen Filmportal-Anwendung.</p>
</section>

<section class="karte">
    <h2>1. Zusammenfassung der Projektanforderungen</h2>
    <p>
        Die folgenden Punkte fassen die maßgeblichen fachlichen und technischen
        Ziele des Kursprojekts in eigenen Worten zusammen.
    </p>

    <div class="aufgabenblock">
        <p>
            Ziel war die Entwicklung eines Filmportals mit öffentlichem Frontend,
            geschütztem Benutzerbereich und separater Administration.
        </p>

        <ul>
            <li>Strukturierung der Anwendung mit Controllern, Models, Views, Templates und Partials</li>
            <li>Umsetzung mit PHP, MySQL, PDO, HTML, CSS, JavaScript und AJAX</li>
            <li>Datenbankschema, Startbefüllung und dokumentierte Migrationsschritte</li>
            <li>Registrierung, Anmeldung, Abmeldung und rollenabhängige Zugriffssteuerung</li>
            <li>Lokale Filmsuche mit AJAX-Vorschlägen, Listenansicht, Detailansicht und Pagination</li>
            <li>Import von Filmdaten über die OMDb API sowie Verarbeitung von JSON und XML</li>
            <li>Speicherung und Download der ursprünglichen OMDb-Antworten</li>
            <li>Kommentare, Kontaktanfragen und interne Antworten der Administration</li>
            <li>Verwaltung von Benutzern und Kommentaren sowie Statistiken, Exporte und Datenbanksicherungen</li>
            <li>Einsatz objektorientierter Konzepte wie Interfaces, abstrakte Klassen, Traits, Type-Hinting und Magic Methods</li>
            <li>Validierung von Eingaben sowie verständliche Fehler- und Erfolgsmeldungen</li>
            <li>Integrierte Projektdokumentation mit UML-Diagrammen, Screenshots und abschließendem Fazit</li>
        </ul>
    </div>
</section>

<section class="karte">
    <h2>2. Projekt, Gruppe und Arbeitsweise</h2>
    <p>Das Filmportal wurde als Gruppenarbeit von <strong>Alexander Claußen</strong> und <strong>Martin Frackowiak</strong> umgesetzt.</p>
    <p>Die Umsetzung wurde als abgestimmte Gruppenarbeit durchgeführt. Aufgabenverständnis, Datenmodell, Funktionsumfang und technische Entscheidungen wurden regelmäßig gemeinsam geprüft und am aktuellen Projektstand ausgerichtet: Frontend und Backend, lokale Datenbank, OMDb als externe Quelle, JSON/XML-Dateien, Kommentare, interne Nachrichten, Export, Backup und Dokumentation.</p>
    <p>Die Anwendung wurde bewusst auf Deutsch umgesetzt. Ausnahmen gibt es dort, wo externe OMDb-Daten englischsprachig geliefert werden, zum Beispiel bei Filmtiteln, Genres, Actors, Plot, Language oder Country. Diese Werte bleiben im Original, damit die importierten Daten nicht verfälscht werden und JSON, XML, Datenbank und Oberfläche nachvollziehbar zusammenpassen.</p>
    <p>Die Hinweise aus dem Unterricht, besonders erst zu analysieren und dann sauber nach MVC, OOP und Datenbankstruktur zu arbeiten, waren für die Umsetzung sehr hilfreich. Dadurch konnten wir das Projekt nicht nur als Sammlung einzelner Seiten, sondern als zusammenhängende Anwendung planen.</p>
</section>

<section class="karte">
    <h2>3. Bedarfsanalyse</h2>
    <p>Aus den Projektanforderungen ergab sich ein Filmportal mit öffentlichem Bereich, geschütztem Benutzerbereich und separatem Backend. Besucher sollen lokale Filme suchen und ansehen können. Registrierte Benutzer sollen Filme holen, Rohdateien nutzen, Kommentare schreiben und Nachrichten an den Administrator senden können. Der Admin soll die Anwendung auswerten, Benutzer und Kommentare verwalten, Nachrichten beantworten, Exporte erzeugen und Backups starten können.</p>

    <h3>3.1 Fachliche Grundentscheidung</h3>
    <p>OMDb ist die externe Datenquelle. Die eigene Datenbank <code>gruppe2</code> ist die lokale Filmsammlung. Nach dem Import arbeitet die Galerie, die Suche, die Einzelansicht, die Kommentarlogik, die Statistik und der Export mit lokalen Daten.</p>

    <h3>3.2 Rollen</h3>
    <ul>
        <li><strong>Öffentliche Besucher:</strong> sehen Galerie, lokale Suche, Einzelansicht, Impressum, Kontakt, About und AGB.</li>
        <li><strong>Registrierte Benutzer:</strong> können Filme aus OMDb holen, JSON/XML-Rohdateien nutzen, Kommentare schreiben und Anfragen an den Admin senden.</li>
        <li><strong>Admin:</strong> verwaltet Benutzer, Kommentare, Anfragen, Statistiken, Exporte, Backup und Dokumentation über eine separate Backend-URL.</li>
    </ul>
</section>

<section class="karte">
    <h2>4. Design, UI und UX</h2>
    <p>Die Oberfläche wurde nicht neutral gehalten, sondern bewusst auf das Thema Film ausgerichtet. Das dunkle Kopf- und Navigationsdesign, warme Akzentfarben, Kartenflächen und filmbezogene Bildelemente sollen sofort vermitteln, dass es sich um ein Filmportal handelt.</p>
    <p>Die Menüführung ist nach Benutzerrollen getrennt: öffentliche Funktionen stehen vorne, der OMDb-Import ist an Login gebunden und das Backend bleibt über eine separate URL erreichbar. Meldungen wurden so formuliert, dass Benutzer verstehen, was passiert ist oder was sie korrigieren müssen.</p>
    <p>Die ausführliche Begründung zur Gestaltung steht zusätzlich in <code>docs/21_ui_ux.md</code>.</p>
</section>

<section class="karte">
    <h2>5. Datenbankmodell</h2>
    <p>Das Datenbankmodell wurde normalisiert aufgebaut, aber passend zur OMDb-Realität nicht unnötig zerlegt. Die Tabelle <code>films</code> enthält die zentralen Filmdaten. <code>actors</code> und <code>genres</code> werden als echte m:n-Beziehungen über <code>films_actors</code> und <code>films_genres</code> geführt.</p>
    <p><code>directors</code>, <code>languages</code> und <code>countries</code> speichern vollständige OMDb-Angaben als wiederverwendbare Werte. Dadurch bleiben zusammengesetzte Angaben wie <code>English, Italian, Latin</code> im Original erhalten.</p>
    <p><code>users</code>, <code>comments</code>, <code>messages</code> und <code>api_files</code> bilden Registrierung, Kommentare, interne Anfragen und gespeicherte JSON/XML-Rohdateien ab.</p>

    <pre><code>films = {id, imdb_id, title, year, runtime, directors_id, languages_id, countries_id, plot, poster, created_at}
actors = {id, actor}
genres = {id, genre}
films_actors = {id, films_id, actors_id}
films_genres = {id, films_id, genres_id}
directors = {id, director}
languages = {id, language}
countries = {id, country}
users = {id, alias, email, passwort, rolle, created_at}
comments = {id, users_id, films_id, comment, created_at}
messages = {id, users_id, name, email, subject, message, answer, status, created_at, answered_at}
api_files = {id, users_id, films_id, imdb_id, format, file_path, created_at}</code></pre>
</section>

<section class="karte">
    <h2>6. Implementierung</h2>

    <h3>6.1 Ordnerstruktur</h3>
    <p>Die Dateien wurden nach Aufgabe und Zuständigkeit in Unterordner aufgeteilt. Dadurch bleibt sichtbar, welche Dateien zum Frontend, Backend, zur Datenbank, zu Models, Views, Dokumentation, Rohdaten und Exporten gehören.</p>

    <pre><code>moviepinion
├── admin
│   ├── bilder
│   │   └── screenshots
│   ├── daten
│   ├── test
│   └── views
│       └── partials
├── backups
├── bilder
├── css
├── daten
│   ├── json
│   └── xml
├── docs
├── exports
│   ├── csv
│   ├── json
│   └── xml
├── includes
├── js
├── migrations
├── models
├── sql
├── test
└── views
    └── partials</code></pre>

    <h3>6.2 Controller und MVC</h3>
    <p>Das Frontend wird über <code>index.php</code> gesteuert, das Backend über <code>admin/index.php</code>. Beide Controller entscheiden über die aktuelle Aktion, laden Models und reichen Ergebnisse an passende Views weiter.</p>
    <p>Navigation, Header, Footer und Meldungen sind als Partials umgesetzt. Dadurch bleiben wiederkehrende Seitenteile zentral gepflegt und die Views enthalten hauptsächlich ihren jeweiligen Inhalt.</p>

    <h3>6.3 OOP-Struktur</h3>
    <p>Die Modelklassen liegen im Ordner <code>models/</code>. Die abstrakten Klassen <code>aDatenbank</code> und <code>aModel</code> bündeln Datenbankverbindung und gemeinsame Modeldaten. Das Interface <code>iDatenbank</code> beschreibt Anlegen und Lesen. Das Interface <code>iLoeschbar</code> wird nur dort genutzt, wo Löschen fachlich gefordert ist.</p>
    <p>Der Trait <code>NachschlageTrait</code> unterstützt die Bereinigung von OMDb-Werten, damit leere Werte und <code>N/A</code> nicht als falsche Nachschlagedatensätze gespeichert werden.</p>

    <h3>6.4 Magic Methods, Type-Hinting und Class-Funktionen</h3>
    <p>Die fünf Magic Methods <code>__construct()</code>, <code>__destruct()</code>, <code>__get()</code>, <code>__set()</code> und <code>__toString()</code> sind in der gemeinsamen Modelbasis nachvollziehbar eingesetzt. Type-Hinting wird unter anderem in Methodenparametern und Rückgaben genutzt. Der <code>instanceof</code>-Operator wird bei der PDO-Verbindung eingesetzt. Als Class-Funktion wird <code>method_exists()</code> verwendet, damit Daten nur dann über Setter oder Verbindungsfunktionen übernommen werden, wenn die jeweilige Methode in der Klasse tatsächlich vorhanden ist.</p>
</section>

<section class="karte">
    <h2>7. OMDb, JSON, XML und Startbefüllung</h2>
    <p>Filmdaten werden aus OMDb geholt. JSON und XML werden auf dieselbe interne Struktur normalisiert, damit der Import in die Datenbank unabhängig vom gewählten Format funktioniert.</p>
    <p>Die Originalantworten werden zusätzlich unter <code>daten/json/</code> und <code>daten/xml/</code> gespeichert. Dadurch bleiben die externen Rohdaten verfügbar und können Benutzern später als Datei angeboten werden.</p>
    <p>Für einen einfachen Projektstart gibt es <code>setup.bat</code>. Die Datei importiert <code>sql/create_database.sql</code> und startet danach <code>sql/filme_befuellen.php</code>, damit die Datenbank direkt mit Startfilmen gefüllt wird.</p>
</section>

<section class="karte">
    <h2>8. Sicherheit, Validierung und Meldungen</h2>
    <ul>
        <li>Passwörter werden mit <code>password_hash()</code> gespeichert und mit <code>password_verify()</code> geprüft.</li>
        <li>Registrierung verlangt gültige E-Mail, Alias, Passwort mit mindestens 6 Zeichen und Passwort-Wiederholung.</li>
        <li>SQL-Abfragen mit Benutzereingaben arbeiten mit Prepared Statements.</li>
        <li>Ausgaben werden mit <code>htmlspecialchars()</code> beziehungsweise Projekt-Hilfsfunktionen abgesichert.</li>
        <li>Backend-Aktionen prüfen Adminrechte und sind nicht in der öffentlichen Navigation sichtbar.</li>
        <li>Downloads prüfen mit <code>realpath()</code>, ob die Datei wirklich im erlaubten Datenordner liegt.</li>
        <li>Fehler- und Erfolgsmeldungen werden verständlich formuliert und nicht als rohe PHP- oder PDO-Fehler ausgegeben.</li>
    </ul>
</section>

<section class="karte">
    <h2>9. UML-Diagramme</h2>
    <p>Die UML-Diagramme ergänzen die textliche Dokumentation um eine visuelle Darstellung der inneren Projektstruktur. Sie machen sichtbar, wie die Datenbanktabellen zusammenhängen und wie die objektorientierten Bestandteile der Anwendung aufgebaut sind.</p>
    <p>Das UML-Datenbankdiagramm beschreibt das relationale Datenmodell des Filmportals. Im Mittelpunkt steht die Tabelle <code>films</code>, in der die lokal übernommenen Kerndaten eines Films gespeichert werden. Wiederholbare Angaben wie Genres und Schauspieler werden über eigene Tabellen und Verbindungstabellen modelliert. Benutzer, Kommentare, interne Nachrichten und gespeicherte API-Dateien sind als eigene fachliche Bereiche abgebildet, damit erkennbar bleibt, welche Daten aus OMDb stammen und welche Daten innerhalb der Anwendung entstehen.</p>
    <p>Das UML-Klassendiagramm dokumentiert die objektorientierte Struktur der Anwendung. Es zeigt die gemeinsame Datenbankbasis, die gemeinsame Modelbasis, die eingesetzten Interfaces, den Trait zur Bereinigung von OMDb-Nachschlagewerten und die konkreten Models. Dadurch wird sichtbar, dass die Anwendung nicht als Sammlung einzelner Skripte aufgebaut ist, sondern über wiederverwendbare Klassen, klare Zuständigkeiten und kontrollierte Datenbankmethoden arbeitet.</p>
    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/uml_datenbank.webp" alt="UML-Datenbankdiagramm des Filmportals">
            <figcaption>UML-Datenbankdiagramm</figcaption>
        </figure>

        <figure>
            <img class="doku-bild" src="bilder/uml_klassen.webp" alt="UML-Klassendiagramm des Filmportals">
            <figcaption>UML-Klassendiagramm</figcaption>
        </figure>
    </div>
</section>

<section class="karte">
    <h2>10. Screenshots</h2>
    <p>Die Screenshots zeigen die fertige Oberfläche des Filmportals. Sie dokumentieren die wichtigsten Benutzer- und Adminbereiche und ergänzen damit den Quellcode um den sichtbaren Nachweis, dass die Funktionen über die Anwendung erreichbar sind.</p>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Header.webp" alt="Frontend: Kopfbereich und Archivsuche">
            <figcaption>Frontend: Kopfbereich und Archivsuche</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Navi.webp" alt="Frontend: Navigation">
            <figcaption>Frontend: Navigation</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Galerie.webp" alt="Frontend: Filmgalerie">
            <figcaption>Frontend: Filmgalerie</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Suche.webp" alt="Frontend: lokale Suche">
            <figcaption>Frontend: lokale Suche</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Kontakt.webp" alt="Frontend: Kontaktformular">
            <figcaption>Frontend: Kontaktformular</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_meineNachrichten.webp" alt="Frontend: eigene Anfragen">
            <figcaption>Frontend: eigene Anfragen</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_About.webp" alt="Frontend: About-Seite">
            <figcaption>Frontend: About-Seite</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Agb.webp" alt="Frontend: AGB-Seite">
            <figcaption>Frontend: AGB-Seite</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Impressum.webp" alt="Frontend: Impressum">
            <figcaption>Frontend: Impressum</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Frontend_Footer.webp" alt="Frontend: Footer">
            <figcaption>Frontend: Footer</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Login.webp" alt="Backend: Login">
            <figcaption>Backend: Login</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Statistics.webp" alt="Backend: Statistiken">
            <figcaption>Backend: Statistiken</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Users.webp" alt="Backend: Benutzerverwaltung">
            <figcaption>Backend: Benutzerverwaltung</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_comments.webp" alt="Backend: Kommentarverwaltung">
            <figcaption>Backend: Kommentarverwaltung</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Messages.webp" alt="Backend: Anfragen">
            <figcaption>Backend: Anfragen</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Exports.webp" alt="Backend: Export">
            <figcaption>Backend: Export</figcaption>
        </figure>
    </div>

    <div class="doku-bilder">
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Backup.webp" alt="Backend: Datenbank-Backup">
            <figcaption>Backend: Datenbank-Backup</figcaption>
        </figure>
        <figure>
            <img class="doku-bild" src="bilder/screenshots/Backend_Documentation.webp" alt="Backend: Dokumentationsseite">
            <figcaption>Backend: Dokumentationsseite</figcaption>
        </figure>
    </div>
</section>

<section class="karte">
    <h2>11. Dokumentationsdateien im Ordner docs</h2>
    <p>Die folgenden 21 Dateien ergänzen die HTML-Dokumentation. Über <strong>mehr</strong> wird die jeweilige Datei im iframe auf dieser Seite geöffnet.</p>

    <div class="doku-dateiliste">
        <?php foreach ($dokumentationsDateien as $datei): ?>
            <article class="doku-datei">
                <h3><?= htmlspecialchars($datei['titel']) ?></h3>
                <p><?= htmlspecialchars($datei['kurz']) ?></p>
                <a class="button button-sekundaer" href="../docs/<?= rawurlencode($datei['datei']) ?>" target="doku_iframe" onclick="document.getElementById('doku-iframe').hidden = false;">mehr</a>
            </article>
        <?php endforeach; ?>
    </div>

    <div id="doku-iframe" class="doku-iframe-bereich" hidden>
        <h3>Vorschau der Dokumentationsdatei</h3>
        <iframe class="doku-iframe" name="doku_iframe" title="Vorschau der Dokumentationsdateien"></iframe>
    </div>
</section>

<section class="karte">
    <h2>12. Tests und Release</h2>
    <p>Vor der Abgabe wurden die zentralen Abläufe geprüft: Galerie, lokale Suche, Registrierung, Login, OMDb-Import, JSON/XML-Speicherung, Einzelansicht, Kommentare, interne Nachrichten, Backend-Statistiken, Export, Backup und die Dokumentationsseite.</p>
    <p>Für die lokale Installation gilt: <code>setup.bat</code> stellt die Datenbank her und startet danach die Startbefüllung. Die Migrationsskripte bleiben als Entwicklungsschritte dokumentiert; für eine frische Installation ist <code>sql/create_database.sql</code> maßgeblich.</p>
</section>

<section class="karte">
    <h2>13. Fazit</h2>

    <h3>Erfahrungen</h3>
    <p>Die größte Erfahrung war, dass ein Filmportal nicht nur aus einzelnen PHP-Seiten besteht. Entscheidend war die saubere Verbindung aus Analyse, lokaler Datenbank, OMDb-Datenquelle, JSON/XML-Dateien, Benutzerrollen, Controllerlogik, Models und verständlicher Oberfläche.</p>
    <p>Die Gruppenarbeit war dabei ein klarer Vorteil. Durch den regelmäßigen fachlichen Austausch konnten Entscheidungen gegengeprüft, Fehler früher erkannt und die Projektanforderungen nicht nur technisch, sondern auch fachlich besser verstanden werden.</p>

    <h3>Erfolge</h3>
    <ul>
        <li>Das Projekt besitzt ein getrenntes Frontend und Backend mit eigenem Backend-Controller.</li>
        <li>Filme können aus OMDb geholt, als JSON/XML gespeichert und lokal in MySQL übernommen werden.</li>
        <li>Die lokale Galerie, Suche, Einzelansicht, Kommentare, Statistiken und Exporte arbeiten mit der eigenen Datenbank.</li>
        <li>Die Anwendung nutzt OOP, PDO, Models, Interfaces, abstrakte Klassen, Trait, Magic Methods, AJAX und Pagination.</li>
        <li>Die Oberfläche greift das Filmthema sichtbar auf und führt Benutzer mit verständlichen Menüs und Meldungen durch die Anwendung.</li>
        <li>Die Dokumentation im Backend zeigt die zusammengefassten Projektanforderungen, Analyse, Umsetzung, ergänzende Dokumentationsdateien, UML-Diagramme und Screenshots.</li>
    </ul>

    <h3>Misserfolge und Schwierigkeiten</h3>
    <ul>
        <li>Die OMDb-Daten sind nicht immer gleich vollständig, daher mussten Werte wie leere Angaben oder <code>N/A</code> kontrolliert bereinigt werden.</li>
        <li>JSON und XML liefern dieselben Inhalte technisch unterschiedlich, besonders XML mit Attributen; deshalb war eine Normalisierung notwendig.</li>
        <li>Die Entscheidung, welche Werte normalisiert und welche als vollständige OMDb-Angabe gespeichert werden, musste sorgfältig abgewogen werden.</li>
        <li>Die Dokumentation musste parallel zur Umsetzung gepflegt werden, damit der Entwicklungsweg nachvollziehbar bleibt.</li>
    </ul>

    <p>Insgesamt ist eine benutzerfreundliche und thematisch passende Frontend-/Backend-Anwendung entstanden. Die Hinweise aus dem Unterricht zur Analyse, zu MVC, OOP, PDO und zur sauberen Projektstruktur haben dabei geholfen, die Anforderungen als zusammenhängende Anwendung umzusetzen.</p>
</section>
