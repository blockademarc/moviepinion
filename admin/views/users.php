<section class="karte">
    <h1>Benutzer</h1>
    <p>Verwalten Sie die registrierten Benutzerkonten des Filmportals.</p>

    <?php if (empty($result)): ?>
        <p>Es sind noch keine Benutzer vorhanden.</p>
    <?php else: ?>
        <table class="tabelle">
            <tr><th>ID</th><th>Alias</th><th>E-Mail</th><th>Rolle</th><th>Aktion</th></tr>
            <?php foreach ($result as $user): ?>
                <tr>
                    <td><?= sichereAusgabe($user['id']) ?></td>
                    <td><?= sichereAusgabe($user['alias']) ?></td>
                    <td><?= sichereAusgabe($user['email']) ?></td>
                    <td><?= sichereAusgabe($user['rolle']) ?></td>
                    <td><?php if ($user['rolle'] !== 'admin'): ?><a class="button button-gefahr" href="index.php?action=userDelete&id=<?= sichereAusgabe($user['id']) ?>">Löschen</a><?php endif; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
