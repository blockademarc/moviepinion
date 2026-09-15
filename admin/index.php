<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/autoload.php';
require_once __DIR__ . '/../includes/funktionen.php';

$action = isset($_GET['action']) ? $_GET['action'] : (istAdmin() ? 'statistics' : 'login');

// Die Statistik ist die Startseite des Backends.
if ($action === 'dashboard') {
    $action = 'statistics';
}

if ($action === 'loginAbsenden') {
    $user = (new User())->login(postWert('alias'), postWert('passwort'));
    if ($user && $user['rolle'] === 'admin') {
        $_SESSION['users_id'] = (int) $user['id'];
        $_SESSION['alias'] = $user['alias'];
        $_SESSION['rolle'] = $user['rolle'];
        weiterleiten('index.php?action=statistics');
    }
    setzeFehler('Login fehlgeschlagen. Bitte prüfen Sie Alias und Passwort.');
    weiterleiten('index.php?action=login');
}

if ($action === 'logout') {
    session_destroy();
    session_start();
    setzeMeldung('Sie wurden abgemeldet.');
    weiterleiten('index.php?action=login');
}

if ($action !== 'login') {
    verlangeAdmin();
}

if ($action === 'commentDelete') {
    (new Comment())->delete((int) getWert('id'));
    setzeMeldung('Kommentar wurde gelöscht.');
    weiterleiten('index.php?action=comments');
}

if ($action === 'userDelete') {
    (new User())->delete((int) getWert('id'));
    setzeMeldung('Benutzerkonto und zugehörige Kommentare wurden gelöscht.');
    weiterleiten('index.php?action=users');
}

if ($action === 'messageAnswer') {
    // Antworten bleiben im System.
    // So kann der Benutzer nach dem Login seine Anfrage und die Admin-Antwort sehen.
    $id = (int) postWert('id');
    $answer = postWert('answer');

    if ($id <= 0 || $answer === '') {
        setzeFehler('Bitte geben Sie eine Antwort ein.');
        weiterleiten('index.php?action=messages');
    }

    (new Message())->answer($id, $answer);
    setzeMeldung('Antwort wurde gespeichert.');
    weiterleiten('index.php?action=messages');
}


if ($action === 'exportJson' || $action === 'exportXml' || $action === 'exportCsv') {
    $filme = (new Film())->exportDaten();
    $format = str_replace('export', '', strtolower($action));
    $datei = 'films_' . date('Ymd_His') . '.' . $format;
    $zielPfad = '';

    if ($format === 'json') {
        erstelleOrdnerWennNoetig(EXPORT_JSON_PFAD);
        $zielPfad = EXPORT_JSON_PFAD . '/' . $datei;
        file_put_contents($zielPfad, json_encode($filme, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    if ($format === 'xml') {
        erstelleOrdnerWennNoetig(EXPORT_XML_PFAD);
        $zielPfad = EXPORT_XML_PFAD . '/' . $datei;
        $xml = new SimpleXMLElement('<films/>');
        foreach ($filme as $film) {
            $node = $xml->addChild('film');
            foreach ($film as $key => $value) {
                $node->addChild($key, htmlspecialchars((string) $value));
            }
        }
        $xml->asXML($zielPfad);
    }

    if ($format === 'csv') {
        erstelleOrdnerWennNoetig(EXPORT_CSV_PFAD);
        $zielPfad = EXPORT_CSV_PFAD . '/' . $datei;
        $fp = fopen($zielPfad, 'w');
        if ($fp) {
            if (!empty($filme)) {
                fputcsv($fp, array_keys($filme[0]), ';');
                foreach ($filme as $film) {
                    fputcsv($fp, $film, ';');
                }
            }
            fclose($fp);
        }
    }

    setzeMeldung('Export wurde gespeichert: ' . basename($zielPfad));
    weiterleiten('index.php?action=exports');
}

if ($action === 'backupCreate') {
    // Der genaue Pfad zu mysqldump kann je nach lokaler XAMPP-Installation unterschiedlich sein.
	$mysqldump = findeMysqldump();
	
    // Das Backup wird als SQL-Datei im Projektordner backups gespeichert.
    // So bleibt die Sicherung direkt beim Projekt und kann vom Admin leicht gefunden werden.
    erstelleOrdnerWennNoetig(BACKUP_PFAD);

    // Datum und Uhrzeit im Dateinamen verhindern, dass ein neues Backup ein älteres überschreibt.
    $dateiname = 'backup_gruppe2_' . date('Y-m-d_H-i-s') . '.sql';
    $datei = BACKUP_PFAD . '/' . $dateiname;

    // mysqldump wird als Werkzeug für die Datenbanksicherung verwendet.
    // -B nimmt die Datenbank selbst in den Dump auf.
    // --add-drop-database ergänzt DROP DATABASE, damit ein späteres Einspielen sauber möglich ist.
    // --result-file schreibt die SQL-Sicherung direkt in die Zieldatei.
    // Das ist unter Windows/XAMPP zuverlässiger als die Umleitung mit >,
    // weil die Datei direkt von mysqldump erzeugt wird.
    $befehl =
        escapeshellarg($mysqldump) . ' -B --add-drop-database ' .
        '--user=' . escapeshellarg(DB_USER) . ' ' .
        (DB_PASS !== '' ? '--password=' . escapeshellarg(DB_PASS) . ' ' : '') .
        escapeshellarg(DB_NAME) . ' ' .
        '--result-file=' . escapeshellarg($datei);

    // exec() führt den Shell-Befehl aus und gibt uns einen Rückgabecode.
    // Rückgabecode 0 bedeutet: mysqldump wurde erfolgreich ausgeführt.
    // 2>&1 sorgt dafür, dass Fehlermeldungen von mysqldump in $ausgabe landen.
    $ausgabe = [];
    $code = 1;
    exec($befehl . ' 2>&1', $ausgabe, $code);

    // Ein Backup ist nur dann brauchbar, wenn die Datei existiert und Inhalt hat.
    // Eine leere SQL-Datei würde später beim Wiederherstellen nichts nützen.
    if ($code === 0 && is_file($datei) && filesize($datei) > 0) {
        setzeMeldung('Backup wurde erstellt: ' . basename($datei));
    } else {
        // Wenn eine leere Datei entstanden ist, wird sie gelöscht.
        // So bleibt im Backup-Ordner keine Datei liegen, die wie ein Backup aussieht,
        // aber tatsächlich keine Daten enthält.
        if (is_file($datei) && filesize($datei) === 0) {
            unlink($datei);
        }

        // Falls mysqldump eine Fehlermeldung geliefert hat, zeigen wir sie an.
        // Falls nicht, bekommt der Admin trotzdem eine verständliche Meldung.
        $fehler = trim(implode(' ', $ausgabe));

        if ($fehler === '') {
            $fehler = 'Es wurde keine gültige Sicherungsdatei erstellt.';
        }
        setzeFehler('Backup konnte nicht erstellt werden: ' . $fehler);
    }
    weiterleiten('index.php?action=backup');
}


$result = null;
$view = $action;

switch ($action) {
    case 'login':
    case 'exports':
    case 'backup':
    case 'documentation':
        break;
    case 'users':
        $result = (new User())->selectAll();
        break;
    case 'comments':
        $result = (new Comment())->selectAll();
        break;
    case 'messages':
        // Nachrichten aus dem Kontaktformular gehen an den Admin und werden hier gesammelt.
        $result = (new Message())->selectAllWithUser();
        break;
    case 'statistics':
        $filmModel = new Film();
        $result = [
            'system' => [
                'benutzer' => count((new User())->selectAll()),
                'kommentare' => count((new Comment())->selectAll()),
                'messages' => (new Message())->countAll(),
                'offene_nachrichten' => (new Message())->countByStatus('offen'),
                'json' => (new ApiFile())->countByFormat('json'),
                'xml' => (new ApiFile())->countByFormat('xml'),
            ],
            'filme' => $filmModel->filmStatistiken(),
        ];
        break;
    default:
        $view = 'statistics';
        $filmModel = new Film();
        $result = [
            'system' => [
                'benutzer' => count((new User())->selectAll()),
                'kommentare' => count((new Comment())->selectAll()),
                'messages' => (new Message())->countAll(),
                'offene_nachrichten' => (new Message())->countByStatus('offen'),
                'json' => (new ApiFile())->countByFormat('json'),
                'xml' => (new ApiFile())->countByFormat('xml'),
            ],
            'filme' => $filmModel->filmStatistiken(),
        ];
        break;
}

require_once __DIR__ . '/views/partials/header.php';
require_once __DIR__ . '/views/partials/__navi.php';
require_once __DIR__ . '/../views/partials/meldungen.php';
require_once __DIR__ . '/views/' . $view . '.php';
require_once __DIR__ . '/views/partials/footer.php';
