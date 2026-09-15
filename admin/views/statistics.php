<?php
$system = $result['system'] ?? [];
$filmSummen = $result['filme']['summen'] ?? [];
$filmTop = $result['filme']['top'] ?? [];

$topText = function (array $top, string $einzahl = 'Film', string $mehrzahl = 'Filme'): string {
    $wert = (string) ($top['wert'] ?? '-');
    $anzahl = (int) ($top['anzahl'] ?? 0);

    if ($anzahl <= 0 || $wert === '' || $wert === '-') {
        return '-';
    }

    return $wert . ' (' . $anzahl . ' ' . ($anzahl === 1 ? $einzahl : $mehrzahl) . ')';
};
?>

<section class="seitenkopf">
    <h1>Übersicht</h1>
    <p>Aktuelle Kennzahlen zum Filmarchiv, zu Benutzeraktivitäten und gespeicherten Dateien.</p>
</section>

<section class="statistik-bereich">
    <h2>Portal</h2>

    <div class="statistik-grid">
        <article class="statistik-karte">
            <span class="statistik-label">Benutzer</span>
            <strong><?= sichereAusgabe($system['benutzer'] ?? 0) ?></strong>
            <p>Registrierte Konten im Portal.</p>
        </article>
        <article class="statistik-karte">
            <span class="statistik-label">Kommentare</span>
            <strong><?= sichereAusgabe($system['kommentare'] ?? 0) ?></strong>
            <p>Veröffentlichte Kommentare zu Filmen.</p>
        </article>
        <article class="statistik-karte">
            <span class="statistik-label">Anfragen</span>
            <strong><?= sichereAusgabe($system['nachrichten'] ?? ($system['messages'] ?? 0)) ?></strong>
            <p>Gesendete Benutzeranfragen.</p>
        </article>
        <article class="statistik-karte">
            <span class="statistik-label">Offene Anfragen</span>
            <strong><?= sichereAusgabe($system['offene_nachrichten'] ?? 0) ?></strong>
            <p>Noch nicht beantwortete Anfragen.</p>
        </article>
        <article class="statistik-karte">
            <span class="statistik-label">JSON-Dateien</span>
            <strong><?= sichereAusgabe($system['json'] ?? 0) ?></strong>
            <p>Gespeicherte JSON-Dateien.</p>
        </article>
        <article class="statistik-karte">
            <span class="statistik-label">XML-Dateien</span>
            <strong><?= sichereAusgabe($system['xml'] ?? 0) ?></strong>
            <p>Gespeicherte XML-Dateien.</p>
        </article>
    </div>
</section>

<section class="statistik-bereich">
    <h2>Filmarchiv</h2>

    <div class="statistik-grid">
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Filme</span>
            <strong><?= sichereAusgabe($filmSummen['filme'] ?? 0) ?></strong>
            <p>Gespeicherte Filme im Archiv.</p>
        </article>
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Schauspieler</span>
            <strong><?= sichereAusgabe($filmSummen['actors'] ?? 0) ?></strong>
            <p>Erfasste Schauspieler im Archiv.</p>
        </article>
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Genres</span>
            <strong><?= sichereAusgabe($filmSummen['genres'] ?? 0) ?></strong>
            <p>Erfasste Genres im Archiv.</p>
        </article>
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Regisseure</span>
            <strong><?= sichereAusgabe($filmSummen['directors'] ?? 0) ?></strong>
            <p>Erfasste Regisseure im Archiv.</p>
        </article>
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Sprachen</span>
            <strong><?= sichereAusgabe($filmSummen['languages'] ?? 0) ?></strong>
            <p>Erfasste Sprachangaben.</p>
        </article>
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Länder</span>
            <strong><?= sichereAusgabe($filmSummen['countries'] ?? 0) ?></strong>
            <p>Erfasste Länderangaben.</p>
        </article>
        <article class="statistik-karte statistik-karte-film">
            <span class="statistik-label">Kommentierte Filme</span>
            <strong><?= sichereAusgabe($filmSummen['filme_mit_kommentaren'] ?? 0) ?></strong>
            <p>Filme mit mindestens einem Kommentar.</p>
        </article>
    </div>

    <div class="karte">
        <h3>Häufige Werte</h3>
        <table class="tabelle statistik-top-tabelle">
            <tr>
                <th>Auswertung</th>
                <th>Ergebnis</th>
            </tr>
            <tr>
                <td>Häufigster Schauspieler</td>
                <td><?= sichereAusgabe($topText($filmTop['actor'] ?? [])) ?></td>
            </tr>
            <tr>
                <td>Häufigstes Genre</td>
                <td><?= sichereAusgabe($topText($filmTop['genre'] ?? [])) ?></td>
            </tr>
            <tr>
                <td>Häufigster Regisseur</td>
                <td><?= sichereAusgabe($topText($filmTop['director'] ?? [])) ?></td>
            </tr>
            <tr>
                <td>Häufigstes Jahr</td>
                <td><?= sichereAusgabe($topText($filmTop['year'] ?? [])) ?></td>
            </tr>
            <tr>
                <td>Häufigste Länderangabe</td>
                <td><?= sichereAusgabe($topText($filmTop['country'] ?? [])) ?></td>
            </tr>
            <tr>
                <td>Häufigste Sprachangabe</td>
                <td><?= sichereAusgabe($topText($filmTop['language'] ?? [])) ?></td>
            </tr>
        </table>
    </div>
</section>
