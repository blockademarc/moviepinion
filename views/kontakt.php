<section class="karte">
    <h1>Kontakt</h1>
    <?php if (!istEingeloggt()): ?>
        <p>Melden Sie sich an, um eine Anfrage zu senden.</p>
        <p><a class="button" href="index.php?action=login">Login öffnen</a></p>
    <?php else: ?>
        <p>Schreiben Sie uns Ihre Frage oder Ihren Hinweis. Die Antwort finden Sie anschließend unter „Meine Anfragen“.</p>
        <form class="formular" action="index.php?action=kontaktSenden" method="post">
            <label>Name <input type="text" name="name" value="<?= sichereAusgabe($result['alias'] ?? '') ?>" required></label>
            <label>E-Mail <input type="email" name="email" value="<?= sichereAusgabe($result['email'] ?? '') ?>" required></label>
            <label>Betreff <input type="text" name="subject" required></label>
            <label>Nachricht <textarea name="message" required></textarea></label>
            <button type="submit">Anfrage senden</button>
        </form>
    <?php endif; ?>
</section>
