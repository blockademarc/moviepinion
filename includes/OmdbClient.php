<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funktionen.php';

class OmdbClient
{
    private string $basisUrl = 'https://www.omdbapi.com/';

    public function ladeFilm(array $parameter, string $format): array
    {
        $format = strtolower($format) === 'xml' ? 'xml' : 'json';
        $parameter['apikey'] = OMDB_API_KEY;
        $parameter['plot'] = 'full';
        $parameter['r'] = $format;

        $url = $this->basisUrl . '?' . http_build_query($parameter);

        try {
            // OMDb liegt außerhalb unseres Projekts.
            // Deshalb kann der Abruf scheitern, obwohl unsere lokale Anwendung korrekt arbeitet.
            $antwort = @file_get_contents($url);
        } catch (Throwable $exception) {
            throw new RuntimeException('OMDb konnte nicht erreicht werden. Bitte Internetverbindung und API-Key prüfen.');
        }

        if ($antwort === false || trim($antwort) === '') {
            throw new RuntimeException('OMDb konnte nicht erreicht werden oder lieferte keine Antwort.');
        }

        return $this->antwortAufbereiten($antwort, $format, $url);
    }

    private function antwortAufbereiten(string $antwort, string $format, string $quelle): array
    {
        $rohDaten = $format === 'xml'
            ? $this->xmlZuArray($antwort)
            : $this->jsonZuArray($antwort);

        $daten = $this->normalisiereOmdbDaten($rohDaten);

        if (($daten['Response'] ?? 'False') !== 'True') {
            throw new RuntimeException($daten['Error'] ?? 'OMDb lieferte keine gültigen Filmdaten.');
        }

        return [
            'raw' => $antwort,
            'data' => $daten,
            'format' => $format,
            'url' => $quelle,
        ];
    }

    public function sucheVorschlaege(string $titel): array
    {
        if ($titel === '' || OMDB_API_KEY === 'BITTE_API_KEY_HIER_EINTRAGEN') {
            return [];
        }

        $url = $this->basisUrl . '?' . http_build_query([
            'apikey' => OMDB_API_KEY,
            's' => $titel,
            'type' => 'movie',
        ]);

        $antwort = @file_get_contents($url);
        if ($antwort === false) {
            return [];
        }

        $daten = json_decode($antwort, true);
        return is_array($daten['Search'] ?? null) ? $daten['Search'] : [];
    }

    private function jsonZuArray(string $antwort): array
    {
        $daten = json_decode($antwort, true);
        if (!is_array($daten)) {
            throw new RuntimeException('JSON konnte nicht gelesen werden.');
        }
        return $daten;
    }

    private function xmlZuArray(string $antwort): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($antwort);

        if ($xml === false) {
            libxml_clear_errors();
            throw new RuntimeException('XML konnte nicht gelesen werden.');
        }

        // OMDb liefert XML als Attribute im movie-Knoten.
        // Die Umwandlung in ein Array sorgt dafür, dass JSON und XML danach gleich weiterverarbeitet werden können.
        $array = json_decode(json_encode($xml), true);
        if (!is_array($array)) {
            throw new RuntimeException('XML konnte nicht in ein Array umgewandelt werden.');
        }

        $movieAttrs = $array['movie']['@attributes'] ?? null;
        $rootAttrs = $array['@attributes'] ?? [];

        if (!is_array($movieAttrs)) {
            return [
                'Response' => ((string) ($rootAttrs['response'] ?? 'False')) === 'True' ? 'True' : 'False',
                'Error' => 'OMDb-XML enthält keinen movie-Knoten.',
            ];
        }

        $movieAttrs['response'] = $rootAttrs['response'] ?? 'False';
        return $movieAttrs;
    }

    private function normalisiereOmdbDaten(array $daten): array
    {
        // JSON nutzt Title/Year/Actors, XML nutzt title/year/actors.
        // Nach dieser Methode arbeiten alle anderen Klassen mit derselben Schreibweise.
        $mapping = [
            'Title' => ['Title', 'title'],
            'Year' => ['Year', 'year'],
            'Runtime' => ['Runtime', 'runtime'],
            'Genre' => ['Genre', 'genre'],
            'Director' => ['Director', 'director'],
            'Actors' => ['Actors', 'actors'],
            'Plot' => ['Plot', 'plot'],
            'Language' => ['Language', 'language'],
            'Country' => ['Country', 'country'],
            'Poster' => ['Poster', 'poster'],
            'imdbID' => ['imdbID'],
            'Response' => ['Response', 'response'],
            'Error' => ['Error', 'error'],
        ];

        $normalisiert = [];
        foreach ($mapping as $ziel => $quellen) {
            foreach ($quellen as $quelle) {
                if (!array_key_exists($quelle, $daten)) {
                    continue;
                }

                $wert = is_array($daten[$quelle]) ? '' : trim((string) $daten[$quelle]);

                // OMDb schreibt fehlende Inhalte oft als N/A.
                // Für unsere lokale Datenbank ist ein leerer Wert besser als ein künstlicher Datensatz mit dem Text N/A.
                if (strtolower($wert) === 'n/a') {
                    $wert = '';
                }

                $normalisiert[$ziel] = $wert;
                break;
            }
        }

        $normalisiert['Response'] = (($normalisiert['Response'] ?? 'False') === 'True') ? 'True' : 'False';

        return $normalisiert;
    }

    public function speichereAntwortAlsDatei(string $raw, string $format, string $imdbId, string $titel = ''): string
    {
        $format = strtolower($format) === 'xml' ? 'xml' : 'json';
        $ordner = $format === 'xml' ? DATEN_XML_PFAD : DATEN_JSON_PFAD;
        erstelleOrdnerWennNoetig($ordner);

        // Die IMDb-ID unterscheidet auch Filme mit identischem Titel.
		$dateiname = $titel !== ''
			? dateinameAusTitel($titel . '_' . $imdbId, $format)
			: dateinameAusImdbId($imdbId, $format);
        $pfad = $ordner . '/' . $dateiname;

        try {
            // Die Datei ist die Originalantwort von OMDb.
            // Sie wird gespeichert, damit der Benutzer den Rohstand später herunterladen kann.
            $bytes = file_put_contents($pfad, $raw);
        } catch (Throwable $exception) {
            throw new RuntimeException('Die OMDb-Datei konnte nicht gespeichert werden. Bitte Schreibrechte im Ordner daten prüfen.');
        }

        if ($bytes === false) {
            throw new RuntimeException('Die OMDb-Datei konnte nicht gespeichert werden. Bitte Schreibrechte im Ordner daten prüfen.');
        }

        return 'daten/' . $format . '/' . $dateiname;
    }
}
