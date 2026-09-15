<?php
session_start();

require_once __DIR__ . '/includes/autoload.php';
require_once __DIR__ . '/includes/funktionen.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'galerie';

// Die Startseite ist im Projekt die Galerie.
if ($action === 'start') {
    $action = 'galerie';
}

// Aktionen mit JSON-Ausgabe, Datei-Download oder Weiterleitung müssen vor dem HTML-Header laufen.
if ($action === 'autosuggestLokal') {
    // Das Auto-Suggest der öffentlichen Suche darf nicht OMDb abfragen.
    // Es sucht nur in der lokalen Datenbank.
    header('Content-Type: application/json; charset=utf-8');
    $term = getWert('term') !== '' ? getWert('term') : getWert('q');
    echo json_encode((new Film())->autosuggestLokal($term), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'autosuggestOmdb') {
    // Die OMDb-Vorschläge gehören zur privaten Importmaske.
    // Wer nicht eingeloggt ist, bekommt hier keine externen Vorschläge und löst keine OMDb-Anfrage aus.
    header('Content-Type: application/json; charset=utf-8');
    if (!istEingeloggt()) {
        echo json_encode([]);
        exit;
    }

    $term = getWert('term') !== '' ? getWert('term') : getWert('q');
    $client = new OmdbClient();
    $vorschlaege = [];

    foreach ($client->sucheVorschlaege($term) as $film) {
        $titel = (string) ($film['Title'] ?? '');
        if ($titel === '') {
            continue;
        }

        $jahr = (string) ($film['Year'] ?? '');
        $imdbId = (string) ($film['imdbID'] ?? '');
        $vorschlaege[] = [
            'typ' => 'OMDb',
            'wert' => $titel,
            'anzeige' => trim($titel . ($jahr !== '' ? ' (' . $jahr . ')' : '')),
            'year' => $jahr,
            'imdb_id' => $imdbId,
        ];
    }

    echo json_encode($vorschlaege, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'rohdateiHerunterladen') {
    verlangeLogin();

    $apiFileModel = new ApiFile();
    $apiFile = $apiFileModel->select((int) getWert('id'));

    if (!$apiFile) {
        setzeFehler('Zu diesem Film wurde keine gespeicherte OMDb-Datei gefunden.');
        weiterleiten('index.php?action=galerie');
    }

    $filmId = (int) ($apiFile['films_id'] ?? 0);
    $format = strtolower((string) ($apiFile['format'] ?? 'json')) === 'xml' ? 'xml' : 'json';
    $realerPfad = $apiFileModel->realerDownloadPfad($apiFile);

    if ($realerPfad === null) {
        setzeFehler('Die Datei konnte nicht heruntergeladen werden. Bitte fügen Sie den Film erneut hinzu.');
        weiterleiten($filmId > 0 ? 'index.php?action=film&id=' . $filmId : 'index.php?action=galerie');
    }

    $contentType = $format === 'xml' ? 'application/xml; charset=utf-8' : 'application/json; charset=utf-8';

    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . basename($realerPfad) . '"');
    header('Content-Length: ' . filesize($realerPfad));
    readfile($realerPfad);
    exit;
}

if ($action === 'logout') {
    session_destroy();
    session_start();
    setzeMeldung('Sie wurden abgemeldet.');
    weiterleiten('index.php?action=start');
}

if ($action === 'loginAbsenden') {
    $alias = postWert('alias');
    $passwort = postWert('passwort');
    $user = (new User())->login($alias, $passwort);

    if ($user) {
        $_SESSION['users_id'] = (int) $user['id'];
        $_SESSION['alias'] = $user['alias'];
        $_SESSION['rolle'] = $user['rolle'];
        setzeMeldung('Sie sind angemeldet.');
        weiterleiten('index.php?action=start');
    }

    setzeFehler('Login fehlgeschlagen. Bitte prüfen Sie Alias und Passwort.');
    weiterleiten('index.php?action=login');
}

if ($action === 'registerAbsenden') {
    $alias = postWert('alias');
    $email = postWert('email');
    $passwort = postWert('passwort');
    $passwortWiederholung = postWert('passwort_wiederholung');

    if ($alias === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setzeFehler('Bitte geben Sie Alias und eine gültige E-Mail-Adresse ein.');
        weiterleiten('index.php?action=register');
    }

    if (strlen($passwort) < 6) {
        setzeFehler('Das Passwort muss mindestens 6 Zeichen lang sein.');
        weiterleiten('index.php?action=register');
    }

    if ($passwort !== $passwortWiederholung) {
        setzeFehler('Die beiden Passwörter stimmen nicht überein.');
        weiterleiten('index.php?action=register');
    }

    try {
        $user = new User(['alias' => $alias, 'email' => $email, 'passwort' => $passwort, 'rolle' => 'benutzer']);
        $user->insert();
        setzeMeldung('Registrierung erfolgreich. Sie können sich jetzt anmelden.');
        weiterleiten('index.php?action=login');
    } catch (Throwable $e) {
        setzeFehler('Registrierung konnte nicht gespeichert werden. Alias oder E-Mail-Adresse ist möglicherweise bereits vergeben.');
        weiterleiten('index.php?action=register');
    }
}

if ($action === 'kontaktSenden') {
    // Laut Aufgabenlogik sendet ein registrierter Benutzer eine Nachricht an den Administrator.
    // Die Nachricht wird in der lokalen Tabelle messages gespeichert und im Backend angezeigt.
    verlangeLogin();

    $name = postWert('name');
    $email = postWert('email');
    $subject = postWert('subject');
    $message = postWert('message');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || $message === '') {
        setzeFehler('Bitte füllen Sie alle Kontaktfelder korrekt aus.');
        weiterleiten('index.php?action=kontakt');
    }

    $message = new Message([
        'users_id' => aktuelleUserId(),
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'status' => 'offen',
    ]);
    $message->insert();
    setzeMeldung('Ihre Anfrage wurde gesendet.');
    weiterleiten('index.php?action=kontakt');
}

if ($action === 'filmDownload') {
    verlangeLogin();

    $format = postWert('format') === 'xml' ? 'xml' : 'json';
    $downloadModus = postWert('download_modus') === 'benutzerdatei' ? 'benutzerdatei' : 'anzeigen';
    $titel = postWert('title');
    $jahr = postWert('year');
    $imdbId = postWert('imdb_id');

    $parameter = [];
    if ($imdbId !== '') {
        $parameter['i'] = $imdbId;
    } elseif ($titel !== '') {
        $parameter['t'] = $titel;
        if ($jahr !== '') {
            $parameter['y'] = $jahr;
        }
    } else {
        setzeFehler('Bitte geben Sie einen Titel oder eine IMDb-ID ein.');
        weiterleiten('index.php?action=suche');
    }

    try {
        $client = new OmdbClient();
        $antwort = $client->ladeFilm($parameter, $format);

        // Beide Suchvarianten speichern intern immer die Originalantwort als Datei.
        // Wenn der Film bereits existiert, wird keine rohe PDO-Exception ausgelöst,
        // sondern der vorhandene Datensatz genutzt und eine verständliche Meldung gesetzt.
        $filmModel = new Film();
        $vorhanden = $filmModel->findeNachImdbId($antwort['data']['imdbID']);
        $filmWarSchonVorhanden = is_array($vorhanden);
        $filmId = $filmWarSchonVorhanden
            ? (int) $vorhanden['id']
            : $filmModel->insertFromOmdbData($antwort['data'], $format);

        $datei = $client->speichereAntwortAlsDatei($antwort['raw'], $format, $antwort['data']['imdbID'], $antwort['data']['Title'] ?? '');

        $apiFile = new ApiFile([
            'users_id' => aktuelleUserId(),
            'films_id' => $filmId,
            'imdb_id' => $antwort['data']['imdbID'],
            'format' => $format,
            'file_path' => $datei,
        ]);
        $apiFileId = $apiFile->insert();

        $zusatz = $filmWarSchonVorhanden
            ? ' Der Film war bereits im Archiv vorhanden.'
            : ' Der Film wurde neu ins Archiv aufgenommen.';

        if ($downloadModus === 'benutzerdatei') {
            setzeMeldung('Der Film wurde übernommen und steht zusätzlich als Datei zum Download bereit.' . $zusatz);
            weiterleiten('index.php?action=suchergebnis&films_id=' . $filmId . '&api_file_id=' . $apiFileId);
        }

        setzeMeldung('Der Film wurde übernommen.' . $zusatz);
        weiterleiten('index.php?action=film&id=' . $filmId);
    } catch (Throwable $e) {
        setzeFehler($e->getMessage());
        weiterleiten('index.php?action=suche');
    }
}

if ($action === 'kommentarSpeichern') {
    verlangeLogin();
    $filmId = (int) postWert('films_id');
    $text = postWert('comment');

    if ($filmId <= 0 || $text === '') {
        setzeFehler('Kommentar konnte nicht gespeichert werden.');
        weiterleiten('index.php?action=film&id=' . $filmId);
    }

    (new Comment(['users_id' => aktuelleUserId(), 'films_id' => $filmId, 'comment' => $text]))->insert();
    setzeMeldung('Kommentar wurde gespeichert.');
    weiterleiten('index.php?action=film&id=' . $filmId);
}

// Ab hier werden normale HTML-Seiten vorbereitet.
$result = null;
$view = $action;

switch ($action) {
    case 'impressum':
    case 'about':
    case 'agb':
    case 'login':
    case 'register':
        break;

    case 'suche':
        // Die OMDb-Download-Maske ist nur für eingeloggte Benutzer aktiv.
        // Filme werden hier von OMDb geholt und anschließend als Rohdatei und als lokaler Datensatz gespeichert.
        break;

    case 'kontakt':
        // Kontakt ist eine Frontend-Seite. Wenn ein Benutzer angemeldet ist,
        // werden seine Daten im Formular vorbefüllt und im Backend als Anfrage sichtbar.
        $result = istEingeloggt() ? (new User())->select(aktuelleUserId()) : null;
        break;

    case 'meineNachrichten':
        // Die Antwort des Admins bleibt im System und wird hier dem angemeldeten Benutzer angezeigt.
        verlangeLogin();
        $result = (new Message())->selectByUser(aktuelleUserId());
        break;

    case 'galerie':
        $filmModel = new Film();
        $proSeite = 4;
        $seite = max(1, (int) getWert('seite'));
        $start = ($seite - 1) * $proSeite;
        $suchbegriff = getWert('q');

        if ($suchbegriff !== '') {
            // Lokale Suche: Die Filme sind bereits in gruppe2 gespeichert und werden mit LIKE gesucht.
            $anzahlTreffer = $filmModel->countSucheLokal($suchbegriff);
            $filme = $filmModel->sucheLokal($suchbegriff, $start, $proSeite);
        } else {
            $anzahlTreffer = $filmModel->countAll();
            $filme = $filmModel->selectAll($start, $proSeite);
        }

        $rohdateienNachFilm = [];
        if (istEingeloggt() && $suchbegriff !== '' && !empty($filme)) {
            $filmIds = array_map(fn($film) => (int) $film['id'], $filme);
            $rohdateienNachFilm = (new ApiFile())->downloadsByFilmIds($filmIds);
        }

        $result = [
            'filme' => $filme,
            'seite' => $seite,
            'seiten' => max(1, (int) ceil($anzahlTreffer / $proSeite)),
            'suchbegriff' => $suchbegriff,
            'anzahl' => $anzahlTreffer,
            'rohdateienNachFilm' => $rohdateienNachFilm,
        ];
        break;

    case 'film':
        $id = (int) getWert('id');
        $film = (new Film())->details($id);
        if (!$film) {
            setzeFehler('Film wurde nicht gefunden.');
            weiterleiten('index.php?action=galerie');
        }
        $result = [
            'film' => $film,
            'comments' => (new Comment())->selectByFilm($id),
            'rohdateien' => (new ApiFile())->downloadsByFilm($id),
        ];
        break;

    case 'suchergebnis':
        verlangeLogin();
        $filmId = (int) getWert('films_id');
        $apiFileId = (int) getWert('api_file_id');
        $film = (new Film())->details($filmId);
        $apiFile = (new ApiFile())->select($apiFileId);

        if (!$film || !$apiFile) {
            setzeFehler('Das Suchergebnis konnte nicht geladen werden.');
            weiterleiten('index.php?action=suche');
        }

        $result = ['film' => $film, 'apiFile' => $apiFile];
        break;

    default:
        $view = 'galerie';
        $filmModel = new Film();
        $proSeite = 4;
        $seite = 1;
        $anzahlTreffer = $filmModel->countAll();
        $result = [
            'filme' => $filmModel->selectAll(0, $proSeite),
            'seite' => $seite,
            'seiten' => max(1, (int) ceil($anzahlTreffer / $proSeite)),
            'suchbegriff' => '',
            'anzahl' => $anzahlTreffer,
        ];
        break;
}

require_once __DIR__ . '/views/partials/header.php';
require_once __DIR__ . '/views/partials/__navi.php';
require_once __DIR__ . '/views/partials/meldungen.php';

require_once __DIR__ . '/views/' . $view . '.php';

require_once __DIR__ . '/views/partials/footer.php';
