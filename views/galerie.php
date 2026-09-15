<section class="seitenkopf">
    <h1>MoviePinion Archiv durchsuchen / Filmgalerie durchstöbern</h1>
    <p>Entdecken Sie Filme im Archiv oder suchen Sie gezielt nach Titel, Jahr, Regie, Besetzung oder Genre.</p>

    <form class="formular suchformular" action="index.php" method="get">
        <input type="hidden" name="action" value="galerie">
        <label>Archivsuche
            <input
                id="lokale-suche-galerie"
                class="ajax-autosuggest"
                type="text"
                name="q"
                value="<?= sichereAusgabe($result['suchbegriff'] ?? '') ?>"
                placeholder="Titel, Jahr, Regisseur, Schauspieler, Genre ..."
                autocomplete="off"
                data-source="lokal"
                data-target="lokale-vorschlaege-galerie"
                data-autosubmit="1"
                data-min-length="1"
            >
            <div id="lokale-vorschlaege-galerie" class="vorschlaege"></div>
        </label>
        <button type="submit">Suchen</button>
        <?php if (!empty($result['suchbegriff'])): ?>
            <a class="button button-sekundaer" href="index.php?action=galerie">Suche zurücksetzen</a>
        <?php endif; ?>
    </form>

    <?php if (!empty($result['suchbegriff'])): ?>
        <p class="hinweis">Treffer für „<?= sichereAusgabe($result['suchbegriff']) ?>“: <?= sichereAusgabe($result['anzahl'] ?? 0) ?></p>
    <?php endif; ?>
</section>

<?php
$rohdateienNachFilm = $result['rohdateienNachFilm'] ?? [];
$hatRohdateienInSuche = false;
foreach (($result['filme'] ?? []) as $film) {
    if (!empty($rohdateienNachFilm[(int) $film['id']])) {
        $hatRohdateienInSuche = true;
        break;
    }
}
?>
<?php if (istEingeloggt() && !empty($result['suchbegriff']) && !empty($result['filme'])): ?>
    <section class="karte download-treffer">
        <h2>Gespeicherte Dateien zu den Treffern</h2>

        <?php if (!$hatRohdateienInSuche): ?>
            <p class="hinweis">Zu diesen Treffern stehen aktuell keine Dateien zum Download bereit.</p>
        <?php else: ?>
            <div class="download-treffer-liste">
                <?php foreach ($result['filme'] as $film): ?>
                    <?php $formate = $rohdateienNachFilm[(int) $film['id']] ?? []; ?>
                    <?php if (empty($formate)) { continue; } ?>
                    <article class="download-treffer-zeile">
                        <strong><?= sichereAusgabe($film['title']) ?><?= !empty($film['year']) ? ' (' . sichereAusgabe($film['year']) . ')' : '' ?></strong>
                        <div class="button-zeile">
                            <?php if (isset($formate['json'])): ?>
                                <a class="button" href="index.php?action=rohdateiHerunterladen&amp;id=<?= sichereAusgabe($formate['json']['id']) ?>">JSON herunterladen</a>
                            <?php endif; ?>
                            <?php if (isset($formate['xml'])): ?>
                                <a class="button" href="index.php?action=rohdateiHerunterladen&amp;id=<?= sichereAusgabe($formate['xml']['id']) ?>">XML herunterladen</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php if (empty($result['filme'])): ?>
    <section class="karte">
        <?php if (!empty($result['suchbegriff'])): ?>
            <p>Zu Ihrer Suche wurden keine Filme gefunden.</p>
        <?php else: ?>
            <p>Im Archiv sind noch keine Filme vorhanden.</p>
        <?php endif; ?>
    </section>
<?php else: ?>
    <?php
    $aktuelleSeite = (int) ($result['seite'] ?? 1);
    $letzteSeite = (int) ($result['seiten'] ?? 1);
    $suchParameter = !empty($result['suchbegriff']) ? '&q=' . rawurlencode($result['suchbegriff']) : '';
    ?>

    <div class="galerie-blaetter">
        <?php if ($aktuelleSeite > 1): ?>
            <a class="galerie-pfeil galerie-pfeil-links" href="index.php?action=galerie&amp;seite=<?= $aktuelleSeite - 1 ?><?= $suchParameter ?>" aria-label="Vorherige Seite">&lt;</a>
        <?php else: ?>
            <span class="galerie-pfeil galerie-pfeil-links deaktiviert" aria-disabled="true">&lt;</span>
        <?php endif; ?>

        <div class="film-grid">
        <?php foreach ($result['filme'] as $film): ?>
            <article class="film-karte">
                <a href="index.php?action=film&id=<?= sichereAusgabe($film['id']) ?>">
                    <?php if (!empty($film['poster']) && $film['poster'] !== 'N/A'): ?>
                        <img class="film-poster" src="<?= sichereAusgabe($film['poster']) ?>" alt="Poster <?= sichereAusgabe($film['title']) ?>">
                    <?php else: ?>
                        <div class="poster-platzhalter">Kein Poster</div>
                    <?php endif; ?>
                    <h2><?= sichereAusgabe($film['title']) ?></h2>
                    <p><?= sichereAusgabe($film['year']) ?></p>
                </a>
            </article>
        <?php endforeach; ?>
        </div>

        <?php if ($aktuelleSeite < $letzteSeite): ?>
            <a class="galerie-pfeil galerie-pfeil-rechts" href="index.php?action=galerie&amp;seite=<?= $aktuelleSeite + 1 ?><?= $suchParameter ?>" aria-label="Naechste Seite">&gt;</a>
        <?php else: ?>
            <span class="galerie-pfeil galerie-pfeil-rechts deaktiviert" aria-disabled="true">&gt;</span>
        <?php endif; ?>
    </div>

    <nav class="pagination" aria-label="Galerie-Seiten">
        <?php for ($i = 1; $i <= $letzteSeite; $i++): ?>
            <a class="<?= $i === $aktuelleSeite ? 'aktiv' : '' ?>" href="index.php?action=galerie&amp;seite=<?= $i ?><?= $suchParameter ?>" <?= $i === $aktuelleSeite ? 'aria-current="page"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
    </nav>
<?php endif; ?>
