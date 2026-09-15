</main>
<?php $aktiveAction = $view ?? ($action ?? ''); ?>
<footer class="fussbereich">
    <nav class="fussnavigation">
        <a class="<?= $aktiveAction === 'kontakt' ? 'ist-aktiv' : '' ?>" href="index.php?action=kontakt" <?= $aktiveAction === 'kontakt' ? 'aria-current="page"' : '' ?>>Kontakt</a>
        <a class="<?= $aktiveAction === 'about' ? 'ist-aktiv' : '' ?>" href="index.php?action=about" <?= $aktiveAction === 'about' ? 'aria-current="page"' : '' ?>>About</a>
        <a class="<?= $aktiveAction === 'impressum' ? 'ist-aktiv' : '' ?>" href="index.php?action=impressum" <?= $aktiveAction === 'impressum' ? 'aria-current="page"' : '' ?>>Impressum</a>
        <a class="<?= $aktiveAction === 'agb' ? 'ist-aktiv' : '' ?>" href="index.php?action=agb" <?= $aktiveAction === 'agb' ? 'aria-current="page"' : '' ?>>AGB</a>
    </nav>
    <p>&copy; MoviePinion</p>
	<p>Externe Filmdaten:
    <a href="https://www.omdbapi.com/" target="_blank" rel="noopener noreferrer">OMDb API</a> von Brian Fritz · <a href="https://creativecommons.org/licenses/by-nc/4.0/" target="_blank" rel="license noopener noreferrer">CC BY-NC 4.0</a>.</p>
</footer>
<script src="js/autosuggest.js"></script>
</body>
</html>
