<?php $meldung = holeMeldung(); $fehler = holeFehler(); ?>
<?php if ($meldung): ?><div class="meldung erfolg"><?= sichereAusgabe($meldung) ?></div><?php endif; ?>
<?php if ($fehler): ?><div class="meldung fehler"><?= sichereAusgabe($fehler) ?></div><?php endif; ?>
