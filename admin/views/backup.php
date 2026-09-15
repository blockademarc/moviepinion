<?php
$backupDateien = glob(__DIR__ . '/../../backups/*.sql') ?: [];
usort($backupDateien, static function (string $a, string $b): int {
    return filemtime($b) <=> filemtime($a);
});
?>
<section class="karte">
    <h1>Datenbank-Backup</h1>
    <p>Hier finden Sie die Gesamtsicherungen der lokalen Datenbank. Erstellen Sie vor größeren Änderungen eine neue Sicherung.</p>
    <p><a class="button" href="index.php?action=backupCreate">Backup erstellen</a></p>

    <?php if (empty($backupDateien)): ?>
        <p class="hinweis">Es wurde noch kein Backup erstellt.</p>
    <?php else: ?>
        <table class="tabelle">
            <tr>
                <th>Datei</th>
                <th>Erstellt am</th>
                <th>Größe</th>
            </tr>
            <?php foreach ($backupDateien as $backup): ?>
                <tr>
                    <td><?= sichereAusgabe(basename($backup)) ?></td>
                    <td><?= sichereAusgabe(date('d.m.Y H:i', filemtime($backup))) ?></td>
                    <td><?= sichereAusgabe(number_format((float) filesize($backup) / 1024, 1, ',', '.')) ?> KB</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
