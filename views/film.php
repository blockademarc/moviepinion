<?php $film = $result['film']; ?>
<section class="film-detail">
    <div>
        <?php if (!empty($film['poster']) && $film['poster'] !== 'N/A'): ?>
            <img src="<?= sichereAusgabe($film['poster']) ?>" alt="Poster <?= sichereAusgabe($film['title']) ?>">
        <?php else: ?>
            <div class="poster-platzhalter">Kein Poster</div>
        <?php endif; ?>
    </div>
    <div>
        <h1><?= sichereAusgabe($film['title']) ?></h1>
        <dl class="detail-liste">
            <dt>Jahr</dt><dd><?= sichereAusgabe($film['year']) ?></dd>
            <dt>IMDb-ID</dt><dd><?= sichereAusgabe($film['imdb_id']) ?></dd>
            <dt>Regie</dt><dd><?= sichereAusgabe($film['directors']) ?></dd>
            <dt>Sprache</dt><dd><?= sichereAusgabe($film['languages']) ?></dd>
            <dt>Land</dt><dd><?= sichereAusgabe($film['countries']) ?></dd>
            <dt>Laufzeit</dt><dd><?= sichereAusgabe($film['runtime']) ?></dd>
            <dt>Besetzung</dt><dd><?= sichereAusgabe($film['actors']) ?></dd>
            <dt>Genre</dt><dd><?= sichereAusgabe($film['genres']) ?></dd>
            <dt>Handlung</dt><dd><?= textMitZeilenumbruechen($film['plot']) ?></dd>
        </dl>
    </div>
</section>

<?php $rohdateien = $result['rohdateien'] ?? []; ?>
<section class="karte">
    <h2>Gespeicherte Dateien</h2>
    <?php if (!istEingeloggt()): ?>
        <p>Melden Sie sich an, um die gespeicherten Dateien zu diesem Film herunterzuladen.</p>
    <?php elseif (empty($rohdateien)): ?>
        <p>Für diesen Film stehen aktuell keine gespeicherten Dateien zum Download bereit.</p>
    <?php else: ?>
        <p>Für diesen Film stehen folgende Dateien zum Download bereit:</p>
        <div class="button-zeile">
            <?php if (isset($rohdateien['json'])): ?>
                <a class="button" href="index.php?action=rohdateiHerunterladen&amp;id=<?= sichereAusgabe($rohdateien['json']['id']) ?>">JSON herunterladen</a>
            <?php endif; ?>
            <?php if (isset($rohdateien['xml'])): ?>
                <a class="button" href="index.php?action=rohdateiHerunterladen&amp;id=<?= sichereAusgabe($rohdateien['xml']['id']) ?>">XML herunterladen</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<section class="karte">
    <h2>Kommentare</h2>

    <?php if (empty($result['comments'])): ?>
        <p>Zu diesem Film gibt es noch keine Kommentare.</p>
    <?php else: ?>
        <?php foreach ($result['comments'] as $kommentar): ?>
            <article class="kommentar">
                <strong><?= sichereAusgabe($kommentar['alias']) ?></strong>
                <p><?= textMitZeilenumbruechen($kommentar['comment']) ?></p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (istEingeloggt()): ?>
        <form class="formular" action="index.php?action=kommentarSpeichern" method="post">
            <input type="hidden" name="films_id" value="<?= sichereAusgabe($film['id']) ?>">
            <label>Kommentar <textarea name="comment" required></textarea></label>
            <button type="submit">Kommentar speichern</button>
        </form>
    <?php else: ?>
        <p>Melden Sie sich an, um einen Kommentar zu schreiben.</p>
    <?php endif; ?>
</section>
