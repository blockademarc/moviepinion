<?php $aktiveAction = $view ?? ($action ?? 'statistics'); ?>
<nav class="hauptnavigation admin-nav">
    <?php if (istAdmin()): ?>
        <a class="<?= $aktiveAction === 'statistics' ? 'ist-aktiv' : '' ?>" href="index.php?action=statistics" <?= $aktiveAction === 'statistics' ? 'aria-current="page"' : '' ?>>Statistiken</a>
        <a class="<?= $aktiveAction === 'users' ? 'ist-aktiv' : '' ?>" href="index.php?action=users" <?= $aktiveAction === 'users' ? 'aria-current="page"' : '' ?>>Benutzer</a>
        <a class="<?= $aktiveAction === 'comments' ? 'ist-aktiv' : '' ?>" href="index.php?action=comments" <?= $aktiveAction === 'comments' ? 'aria-current="page"' : '' ?>>Kommentare</a>
        <a class="<?= $aktiveAction === 'messages' ? 'ist-aktiv' : '' ?>" href="index.php?action=messages" <?= $aktiveAction === 'messages' ? 'aria-current="page"' : '' ?>>Anfragen</a>
        <a class="<?= $aktiveAction === 'exports' ? 'ist-aktiv' : '' ?>" href="index.php?action=exports" <?= $aktiveAction === 'exports' ? 'aria-current="page"' : '' ?>>Export</a>
        <a class="<?= $aktiveAction === 'backup' ? 'ist-aktiv' : '' ?>" href="index.php?action=backup" <?= $aktiveAction === 'backup' ? 'aria-current="page"' : '' ?>>Backup</a>
        <a class="<?= $aktiveAction === 'documentation' ? 'ist-aktiv' : '' ?>" href="index.php?action=documentation" <?= $aktiveAction === 'documentation' ? 'aria-current="page"' : '' ?>>Dokumentation</a>
        <a href="index.php?action=logout">Logout</a>
    <?php endif; ?>
</nav>
