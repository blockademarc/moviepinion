<section class="karte">
    <h1>Kommentare</h1>
    <p>Prüfen und entfernen Sie Kommentare, die nicht zum Filmportal passen.</p>

    <?php if (empty($result)): ?>
        <p>Es sind noch keine Kommentare vorhanden.</p>
    <?php else: ?>
        <table class="tabelle">
            <tr><th>ID</th><th>Film</th><th>Benutzer</th><th>Kommentar</th><th>Aktion</th></tr>
            <?php foreach ($result as $kommentar): ?>
                <tr>
                    <td><?= sichereAusgabe($kommentar['id']) ?></td>
                    <td><?= sichereAusgabe($kommentar['title']) ?></td>
                    <td><?= sichereAusgabe($kommentar['alias']) ?></td>
                    <td><?= sichereAusgabe($kommentar['comment']) ?></td>
                    <td><a class="button button-gefahr" href="index.php?action=commentDelete&id=<?= sichereAusgabe($kommentar['id']) ?>">Löschen</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
