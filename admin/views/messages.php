<section class="karte">
    <h1>Anfragen</h1>
    <p>Bearbeiten Sie die Anfragen der angemeldeten Benutzer.</p>

    <?php if (empty($result)): ?>
        <p>Es sind noch keine Anfragen vorhanden.</p>
    <?php else: ?>
        <table class="tabelle">
            <tr>
                <th>Datum</th>
                <th>Status</th>
                <th>Benutzer</th>
                <th>Absender</th>
                <th>Anfrage</th>
                <th>Antwort</th>
            </tr>
            <?php foreach ($result as $message): ?>
                <tr>
                    <td><?= sichereAusgabe($message['created_at']) ?></td>
                    <td><?= sichereAusgabe($message['status']) ?></td>
                    <td><?= sichereAusgabe($message['alias'] ?? '-') ?></td>
                    <td>
                        <?= sichereAusgabe($message['name']) ?><br>
                        <?= sichereAusgabe($message['email']) ?>
                    </td>
                    <td>
                        <strong><?= sichereAusgabe($message['subject']) ?></strong><br>
                        <?= textMitZeilenumbruechen($message['message']) ?>
                    </td>
                    <td>
                        <?php if (!empty($message['answer'])): ?>
                            <p><strong>Bisherige Antwort:</strong></p>
                            <p><?= textMitZeilenumbruechen($message['answer']) ?></p>
                            <p><small>Beantwortet am: <?= sichereAusgabe($message['answered_at'] ?? '-') ?></small></p>
                        <?php endif; ?>

                        <form class="formular kleinformular" action="index.php?action=messageAnswer" method="post">
                            <input type="hidden" name="id" value="<?= sichereAusgabe($message['id']) ?>">
                            <label>Antwort
                                <textarea name="answer" rows="4" required><?= sichereAusgabe($message['answer'] ?? '') ?></textarea>
                            </label>
                            <button type="submit">Antwort speichern</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
