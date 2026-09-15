<section class="karte">
    <h1>OMDb-Film zum MoviePinion Archiv-hinzufügen</h1>
    <?php if (!istEingeloggt()): ?>
        <p>Melden Sie sich an, um neue Filme zum Archiv hinzuzufügen.</p>
        <p><a class="button" href="index.php?action=login">Login öffnen</a></p>
    <?php else: ?>
        <p>Suchen Sie nach einem Filmtitel oder geben Sie direkt eine IMDb-ID ein.</p>

        <form class="formular" action="index.php?action=filmDownload" method="post">
            <label>Titel
                <input
                    id="omdb-filmsuche"
                    class="ajax-autosuggest"
                    type="text"
                    name="title"
                    autocomplete="off"
                    placeholder="z. B. The Godfather"
                    data-source="omdb"
                    data-target="omdb-vorschlaege"
                    data-toggle="omdb_ajax_aktiv"
                    data-min-length="3"
                >
                <div id="omdb-vorschlaege" class="vorschlaege"></div>
            </label>
            <label>Jahr optional <input type="text" name="year" placeholder="z. B. 1972"></label>
            <label>IMDb-ID optional <input type="text" name="imdb_id" placeholder="z. B. tt0068646"></label>
            <label class="checkbox-zeile">
                <input id="omdb_ajax_aktiv" type="checkbox" checked>
                OMDb-AJAX-Vorschläge anzeigen
            </label>
            <label>Dateiformat
                <select name="format">
                    <option value="json">JSON</option>
                    <option value="xml">XML</option>
                </select>
            </label>

            <div class="button-zeile">
                <button type="submit" name="download_modus" value="anzeigen">Film übernehmen</button>
                <button type="submit" name="download_modus" value="benutzerdatei">Film übernehmen und Datei herunterladen</button>
            </div>
        </form>

    <?php endif; ?>
</section>
