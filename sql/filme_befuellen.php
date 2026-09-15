<?php
// Dieses Skript befüllt die frisch angelegte Datenbank mit einigen Startfilmen.
// Es wird in der Kommandozeile ausgeführt und benutzt dieselben Klassen wie die Website.
// Dadurch entsteht kein zweiter Importweg neben der Anwendung, sondern nur eine kurze Startbefüllung.

if (PHP_SAPI !== 'cli') {
    echo "Dieses Skript ist nur für die Kommandozeile gedacht." . PHP_EOL;
    exit(1);
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/funktionen.php';
require_once __DIR__ . '/../includes/autoload.php';

if (OMDB_API_KEY === '' || OMDB_API_KEY === 'BITTE_API_KEY_HIER_EINTRAGEN') {
    echo "Bitte zuerst den OMDb-API-Key in includes/config.php eintragen." . PHP_EOL;
    exit(1);
}

// Titel und Jahr reichen für diese Startliste aus.
// Falls OMDb bei einem Film trotzdem den falschen Treffer liefert,
// kann der Eintrag später auf imdb_id umgestellt werden, z. B. ['imdb_id' => 'tt0068646'].
$filme = [
    ['titel' => 'The Green Mile', 'jahr' => '1999'],
    ['titel' => 'Dances with Wolves', 'jahr' => '1990'],
    ['titel' => 'Braveheart', 'jahr' => '1995'],
    ['titel' => 'Titanic', 'jahr' => '1997'],
    ['titel' => 'The Godfather', 'jahr' => '1972'],
    ['titel' => 'Gamer', 'jahr' => '2009'],
    ['titel' => 'Nineteen Eighty-Four', 'jahr' => '1984'],
    ['titel' => 'The Matrix', 'jahr' => '1999'],
    ['titel' => 'Ready Player One', 'jahr' => '2018'],
    ['titel' => 'The Truman Show', 'jahr' => '1998'],
    ['titel' => 'V for Vendetta', 'jahr' => '2006'],
    ['titel' => 'Brazil', 'jahr' => '1985'],
    ['titel' => 'The Running Man', 'jahr' => '1987'],
	['titel' => 'Dogville', 'jahr' => '2003'],
	['titel' => 'Beautiful Thing', 'jahr' => '1996'],
	['titel' => 'The Stepford Wives', 'jahr' => '2004'],
	['titel' => 'The Others', 'jahr' => '2001'],
	['titel' => 'Dangerous Liaisons', 'jahr' => '1988'],
];

$formate = ['json', 'xml'];
$client = new OmdbClient();
$erfolgreich = 0;
$fehler = 0;

echo "Startbefüllung für gruppe2" . PHP_EOL;
echo "--------------------------------" . PHP_EOL;

foreach ($filme as $film) {
    $anzeige = $film['imdb_id'] ?? (($film['titel'] ?? '') . (!empty($film['jahr']) ? ' (' . $film['jahr'] . ')' : ''));
    echo PHP_EOL . "Film: " . $anzeige . PHP_EOL;

    foreach ($formate as $format) {
        try {
            $parameter = [];

            // Die IMDb-ID ist am genauesten. Die Startliste arbeitet aber bewusst mit Titel und Jahr,
            // weil die Startliste dadurch leichter lesbar bleibt.
            if (!empty($film['imdb_id'])) {
                $parameter['i'] = trim((string) $film['imdb_id']);
            } else {
                $parameter['t'] = trim((string) ($film['titel'] ?? ''));

                if (!empty($film['jahr'])) {
                    $parameter['y'] = trim((string) $film['jahr']);
                }
            }

            if (empty($parameter['i']) && empty($parameter['t'])) {
                throw new RuntimeException('Kein Titel und keine IMDb-ID angegeben.');
            }

            // Der OMDb-Client holt JSON oder XML und normalisiert danach beide Formate gleich.
            // Film::insertFromOmdbData() übernimmt dann die gleiche Logik wie die Webanwendung:
            // trimmen, Pflichtwerte prüfen, Nachschlagewerte speichern und m:n-Tabellen füllen.
            $antwort = $client->ladeFilm($parameter, $format);
            $filmModel = new Film();
            $filmId = $filmModel->insertFromOmdbData($antwort['data'], $format);

            // Zusätzlich wird die Originalantwort von OMDb gespeichert.
            // Damit gibt es später in Galerie und Einzelansicht echte JSON-/XML-Dateien zum Herunterladen.
            $datei = $client->speichereAntwortAlsDatei(
                $antwort['raw'],
                $format,
                $antwort['data']['imdbID'],
                $antwort['data']['Title'] ?? ''
            );

            $apiFile = new ApiFile([
                'users_id' => null,
                'films_id' => $filmId,
                'imdb_id' => $antwort['data']['imdbID'],
                'format' => $format,
                'file_path' => $datei,
            ]);
            $apiFile->insert();

            echo "  OK: " . strtoupper($format) . " gespeichert" . PHP_EOL;
            $erfolgreich++;
        } catch (Throwable $e) {
            echo "  FEHLER bei " . strtoupper($format) . ": " . $e->getMessage() . PHP_EOL;
            $fehler++;
        }
    }
}

echo PHP_EOL . "--------------------------------" . PHP_EOL;
echo "Fertig. Erfolgreiche Vorgänge: " . $erfolgreich . PHP_EOL;
echo "Fehler: " . $fehler . PHP_EOL;

exit($fehler > 0 ? 1 : 0);
