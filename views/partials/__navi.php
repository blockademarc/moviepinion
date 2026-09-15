<?php $aktiveAction = $view ?? ($action ?? 'galerie'); ?>
<nav class="hauptnavigation">
    <a class="<?= in_array($aktiveAction, ['galerie', 'film'], true) ? 'ist-aktiv' : '' ?>" href="index.php?action=galerie" <?= in_array($aktiveAction, ['galerie', 'film'], true) ? 'aria-current="page"' : '' ?>>Archiv</a>
    <a class="<?= in_array($aktiveAction, ['suche', 'suchergebnis'], true) ? 'ist-aktiv' : '' ?>" href="index.php?action=suche" <?= in_array($aktiveAction, ['suche', 'suchergebnis'], true) ? 'aria-current="page"' : '' ?>>Film hinzufügen</a>

    <span class="loginstatus">
        <?php if (istEingeloggt()): ?>
            Angemeldet als <?= sichereAusgabe($_SESSION['alias'] ?? '') ?>
            <a class="<?= $aktiveAction === 'meineNachrichten' ? 'ist-aktiv' : '' ?>" href="index.php?action=meineNachrichten" <?= $aktiveAction === 'meineNachrichten' ? 'aria-current="page"' : '' ?>>Meine Anfragen</a>
            <a href="index.php?action=logout">Logout</a>
        <?php else: ?>
            <a class="<?= $aktiveAction === 'login' ? 'ist-aktiv' : '' ?>" href="index.php?action=login" <?= $aktiveAction === 'login' ? 'aria-current="page"' : '' ?>>Login</a>
            <a class="<?= $aktiveAction === 'register' ? 'ist-aktiv' : '' ?>" href="index.php?action=register" <?= $aktiveAction === 'register' ? 'aria-current="page"' : '' ?>>Registrierung</a>
        <?php endif; ?>
    </span>
</nav>
