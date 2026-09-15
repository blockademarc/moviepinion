<section class="karte">
    <h1>Meine Anfragen</h1>
    <p>Hier finden Sie Ihre gesendeten Anfragen und die dazugehörigen Antworten.</p>

    <?php if (empty($result)): ?>
        <p>Sie haben bisher keine Anfrage gesendet.</p>
        <p><a class="button" href="index.php?action=kontakt">Anfrage schreiben</a></p>
    <?php else: ?>
        <table class="tabelle">
            <tr>
                <th>Datum</th>
                <th>Status</th>
                <th>Betreff / Nachricht</th>
                <th>Antwort</th>
            </tr>
            <?php foreach ($result as $message): ?>
                <tr>
                    <td><?= sichereAusgabe($message['created_at']) ?></td>
                    <td><?= sichereAusgabe($message['status']) ?></td>
                    <td>
                        <strong><?= sichereAusgabe($message['subject']) ?></strong><br>
                        <?= textMitZeilenumbruechen($message['message']) ?>
                    </td>
                    <td>
                        <?php if (!empty($message['answer'])): ?>
                            <?= textMitZeilenumbruechen($message['answer']) ?><br>
                            <small>Beantwortet am: <?= sichereAusgabe($message['answered_at'] ?? '-') ?></small>
                        <?php else: ?>
                            <em>Noch keine Antwort vorhanden.</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
