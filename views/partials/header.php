<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoviePinion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="kopfbereich">
    <a class="logo-link" href="index.php?action=galerie">
        <img class="logo" src="bilder/moviepinion_logo.svg" alt="MoviePinion">
    </a>

    <form class="kopf-suche" action="index.php" method="get">
        <input type="hidden" name="action" value="galerie">
        <label>Archivsuche
            <input
                id="lokale-suche-kopf"
                class="ajax-autosuggest"
                type="text"
                name="q"
                value="<?= sichereAusgabe(getWert('q')) ?>"
                placeholder="Titel, Jahr, Schauspieler ..."
                autocomplete="off"
                data-source="lokal"
                data-target="lokale-vorschlaege-kopf"
                data-autosubmit="1"
                data-min-length="1"
            >
            <div id="lokale-vorschlaege-kopf" class="vorschlaege"></div>
        </label>
        <button type="submit">Archiv durchsuchen</button>
    </form>
</header>
<main class="hauptinhalt">
