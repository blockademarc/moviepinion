<section class="karte klein">
    <h1>Registrierung</h1>
    <p>Erstellen Sie ein Benutzerkonto, um das Filmarchiv aktiv mitzugestalten.</p>
    <form class="formular" action="index.php?action=registerAbsenden" method="post">
        <label>Alias <input type="text" name="alias" required></label>
        <label>E-Mail <input type="email" name="email" required></label>
        <label>Passwort <input type="password" name="passwort" required minlength="6"></label>
        <label>Passwort wiederholen <input type="password" name="passwort_wiederholung" required minlength="6"></label>
        <button type="submit">Jetzt registrieren</button>
    </form>
</section>
