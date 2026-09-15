<?php $film = $result['film']; $apiFile = $result['apiFile']; ?>
<section class="karte">
    <h1>Film übernommen</h1>
    <p>Der Film wurde dem Archiv hinzugefügt. Die gespeicherte <?= sichereAusgabe(strtoupper($apiFile['format'])) ?>-Datei steht zum Download bereit.</p>

    <div class="suchergebnis-box">
        <?php if (!empty($film['poster']) && $film['poster'] !== 'N/A'): ?>
            <img class="suchergebnis-poster" src="<?= sichereAusgabe($film['poster']) ?>" alt="Poster <?= sichereAusgabe($film['title']) ?>">
        <?php else: ?>
            <div class="poster-platzhalter">Kein Poster</div>
        <?php endif; ?>
        <div>
            <h2><?= sichereAusgabe($film['title']) ?></h2>
            <p><strong>Jahr:</strong> <?= sichereAusgabe($film['year']) ?></p>
            <p><strong>IMDb-ID:</strong> <?= sichereAusgabe($film['imdb_id']) ?></p>
            <p><strong>Datei:</strong> <?= sichereAusgabe($apiFile['file_path']) ?></p>
            <p class="button-zeile">
                <a class="button" href="index.php?action=film&amp;id=<?= sichereAusgabe($film['id']) ?>">Film ansehen</a>
                <a class="button" href="index.php?action=rohdateiHerunterladen&amp;id=<?= sichereAusgabe($apiFile['id']) ?>"><?= sichereAusgabe(strtoupper($apiFile['format'])) ?> herunterladen</a>
            </p>
        </div>
    </div>
</section>
