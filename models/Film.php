<?php
require_once __DIR__ . '/aModel.php';

class Film extends aModel
{
    private ?int $id = null;
    private string $imdb_id = '';
    private string $title = '';
    private string $year = '';
    private string $runtime = '';
    private ?int $directors_id = null;
    private ?int $languages_id = null;
    private ?int $countries_id = null;
    private string $plot = '';
    private string $poster = '';

    public function setId(mixed $id): void { $this->id = $id !== null ? (int) $id : null; $this->values['id'] = $this->id; }
    public function setImdb_id(mixed $v): void { $this->imdb_id = trim((string) $v); $this->values['imdb_id'] = $this->imdb_id; }
    public function setTitle(mixed $v): void { $this->title = trim((string) $v); $this->values['title'] = $this->title; }
    public function setYear(mixed $v): void { $this->year = trim((string) $v); $this->values['year'] = $this->year; }
    public function setRuntime(mixed $v): void { $this->runtime = trim((string) $v); $this->values['runtime'] = $this->runtime; }
    public function setDirectors_id(mixed $v): void { $this->directors_id = $v !== null ? (int) $v : null; $this->values['directors_id'] = $this->directors_id; }
    public function setLanguages_id(mixed $v): void { $this->languages_id = $v !== null ? (int) $v : null; $this->values['languages_id'] = $this->languages_id; }
    public function setCountries_id(mixed $v): void { $this->countries_id = $v !== null ? (int) $v : null; $this->values['countries_id'] = $this->countries_id; }
    public function setPlot(mixed $v): void { $this->plot = trim((string) $v); $this->values['plot'] = $this->plot; }
    public function setPoster(mixed $v): void { $this->poster = trim((string) $v); $this->values['poster'] = $this->poster; }

    public function insert()
    {
        $sql = "INSERT INTO films
                (imdb_id, title, year, runtime, directors_id, languages_id, countries_id, plot, poster)
                VALUES
                (:imdb_id, :title, :year, :runtime, :directors_id, :languages_id, :countries_id, :plot, :poster)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':imdb_id' => $this->imdb_id,
            ':title' => $this->title,
            ':year' => $this->year,
            ':runtime' => $this->runtime,
            ':directors_id' => $this->directors_id,
            ':languages_id' => $this->languages_id,
            ':countries_id' => $this->countries_id,
            ':plot' => $this->plot,
            ':poster' => $this->poster,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        // Die Film-Tabelle enthält nur die direkten Filmdaten.
        // Director, Language und Country stehen in kleinen Tabellen und werden hier wieder dazugelesen.
        $sql = "SELECT films.*, directors.director, languages.language, countries.country
                FROM films
                LEFT JOIN directors ON directors.id = films.directors_id
                LEFT JOIN languages ON languages.id = films.languages_id
                LEFT JOIN countries ON countries.id = films.countries_id
                WHERE films.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll(int $start = 0, int $anzahl = 4)
    {
        // Die Galerie zeigt immer nur eine kleine Anzahl Filme pro Seite.
        // LIMIT bildet genau diese Paginierung ab.
        $stmt = $this->db->prepare("SELECT * FROM films ORDER BY id DESC LIMIT :start, :anzahl");
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':anzahl', $anzahl, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(id) FROM films");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function findeNachImdbId(string $imdbId): ?array
    {
        $imdbId = trim($imdbId);
        if ($imdbId === '') {
            return null;
        }

        $stmt = $this->db->prepare("SELECT * FROM films WHERE imdb_id = :imdb_id");
        $stmt->execute([':imdb_id' => $imdbId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insertFromOmdbData(array $daten, string $format): int
    {
        // Vor dem Speichern werden alle OMDb-Werte bereinigt.
        // Dadurch entstehen keine Datensätze, die nur aus Leerzeichen, N/A oder leeren Pflichtwerten bestehen.
        $imdbId = $this->omdbText($daten, 'imdbID');
        $title = $this->omdbText($daten, 'Title');

        if ($imdbId === '') {
            throw new RuntimeException('OMDb-Daten enthalten keine IMDb-ID.');
        }

        if ($title === '') {
            throw new RuntimeException('OMDb-Daten enthalten keinen Filmtitel.');
        }

        $vorhanden = $this->findeNachImdbId($imdbId);
        if ($vorhanden) {
            return (int) $vorhanden['id'];
        }

        // Director, Language und Country werden als ganze OMDb-Werte gespeichert.
        // Actors und Genres werden danach als echte m:n-Beziehungen angelegt.
        $directorsId = (new Director())->insertOrSelectId($this->omdbText($daten, 'Director'));
        $languagesId = (new Language())->insertOrSelectId($this->omdbText($daten, 'Language'));
        $countriesId = (new Country())->insertOrSelectId($this->omdbText($daten, 'Country'));

        $this->setDaten([
            'imdb_id' => $imdbId,
            'title' => $title,
            'year' => $this->omdbText($daten, 'Year'),
            'runtime' => $this->omdbText($daten, 'Runtime'),
            'directors_id' => $directorsId,
            'languages_id' => $languagesId,
            'countries_id' => $countriesId,
            'plot' => $this->omdbText($daten, 'Plot'),
            'poster' => $this->omdbText($daten, 'Poster'),
        ]);

        $filmId = $this->insert();
        $this->speichereListenwerte($filmId, $daten);

        return $filmId;
    }

    private function omdbText(array $daten, string $feld): string
    {
        $wert = trim((string) ($daten[$feld] ?? ''));
        return strtolower($wert) === 'n/a' ? '' : $wert;
    }

    private function speichereListenwerte(int $filmId, array $daten): void
    {
        // Nur Actors und Genres werden zerlegt.
        // Bei diesen beiden Feldern kann ein einzelner Wert zu vielen Filmen gehören und ein Film kann viele Werte besitzen.
        $this->speichereNamenMitRelation($filmId, zerlegeListe($daten['Genre'] ?? ''), new Genre(), new FilmGenre());
        $this->speichereNamenMitRelation($filmId, zerlegeListe($daten['Actors'] ?? ''), new Actor(), new FilmActor());
    }

    private function speichereNamenMitRelation(int $filmId, array $namen, object $nameModel, object $relationModel): void
    {
        foreach ($namen as $name) {
            if (!method_exists($nameModel, 'insertOrSelectId')) {
                continue;
            }

            $zielId = $nameModel->insertOrSelectId($name);
            if ($zielId !== null && method_exists($relationModel, 'insertVerbindung')) {
                $relationModel->insertVerbindung($filmId, $zielId);
            }
        }
    }

    public function details(int $id): ?array
    {
        $film = $this->select($id);
        if (!$film) {
            return null;
        }

        $film['genres'] = $this->listeFuerFilm('genres', 'genre', 'films_genres', 'genres_id', $id);
        $film['actors'] = $this->listeFuerFilm('actors', 'actor', 'films_actors', 'actors_id', $id);
        $film['directors'] = (string) ($film['director'] ?? '');
        $film['languages'] = (string) ($film['language'] ?? '');
        $film['countries'] = (string) ($film['country'] ?? '');

        return $film;
    }

    private function listeFuerFilm(string $tabelle, string $wertFeld, string $relation, string $zielFeld, int $filmId): string
    {
        // Actors und Genres liegen in Zwischentabellen.
        // GROUP_CONCAT macht daraus eine lesbare Liste für die Einzelansicht und den Export.
        $sql = "SELECT GROUP_CONCAT(DISTINCT {$tabelle}.{$wertFeld} SEPARATOR ', ') AS liste
                FROM {$relation}
                JOIN {$tabelle} ON {$tabelle}.id = {$relation}.{$zielFeld}
                WHERE {$relation}.films_id = :films_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':films_id' => $filmId]);
        return (string) ($stmt->fetchColumn() ?: '');
    }

    public function autosuggestLokal(string $term): array
    {
        // Das Auto-Suggest gehört zur lokalen Suche und arbeitet nur mit der eigenen Datenbank.
        // Für normale Vorschläge wird nach dem Anfang gesucht, weil der Benutzer während des Tippens passende Ergänzungen erwartet.
        // Bei Regisseur, Sprache und Land speichern wir aber komplette OMDb-Angaben wie "English, Italian, Latin".
        // Dort muss auch innerhalb des Textes gesucht werden, sonst würden zweite oder dritte Werte nie als Vorschlag auftauchen.
        $term = trim($term);
        if ($term === '') {
            return [];
        }

        $likeAnfang = $term . '%';
        $likeImText = '%' . $term . '%';
        $vorschlaege = [];

        $queries = [
            [
                'typ' => 'Film',
                'sql' => "SELECT DISTINCT title AS wert, CONCAT(title, ' (', year, ')') AS anzeige
                          FROM films
                          WHERE title LIKE :term
                          ORDER BY title
                          LIMIT 3",
                'suchart' => 'anfang',
            ],
            [
                'typ' => 'Jahr',
                'sql' => "SELECT DISTINCT year AS wert, year AS anzeige
                          FROM films
                          WHERE year LIKE :term AND year <> ''
                          ORDER BY year
                          LIMIT 3",
                'suchart' => 'anfang',
            ],
            [
                'typ' => 'IMDb-ID',
                'sql' => "SELECT DISTINCT imdb_id AS wert, imdb_id AS anzeige
                          FROM films
                          WHERE imdb_id LIKE :term AND imdb_id <> ''
                          ORDER BY imdb_id
                          LIMIT 3",
                'suchart' => 'anfang',
            ],
            [
                'typ' => 'Schauspieler',
                'sql' => "SELECT DISTINCT actor AS wert, actor AS anzeige
                          FROM actors
                          WHERE actor LIKE :term
                          ORDER BY actor
                          LIMIT 3",
                'suchart' => 'anfang',
            ],
            [
                'typ' => 'Regisseur',
                'sql' => "SELECT DISTINCT director AS wert, director AS anzeige
                          FROM directors
                          WHERE director LIKE :term
                          ORDER BY director
                          LIMIT 3",
                'suchart' => 'im_text',
            ],
            [
                'typ' => 'Genre',
                'sql' => "SELECT DISTINCT genre AS wert, genre AS anzeige
                          FROM genres
                          WHERE genre LIKE :term
                          ORDER BY genre
                          LIMIT 3",
                'suchart' => 'anfang',
            ],
            [
                'typ' => 'Land',
                'sql' => "SELECT DISTINCT country AS wert, country AS anzeige
                          FROM countries
                          WHERE country LIKE :term
                          ORDER BY country
                          LIMIT 3",
                'suchart' => 'im_text',
            ],
            [
                'typ' => 'Sprache',
                'sql' => "SELECT DISTINCT language AS wert, language AS anzeige
                          FROM languages
                          WHERE language LIKE :term
                          ORDER BY language
                          LIMIT 3",
                'suchart' => 'im_text',
            ],
        ];

        foreach ($queries as $abfrage) {
            $stmt = $this->db->prepare($abfrage['sql']);
            $stmt->execute([
                ':term' => $abfrage['suchart'] === 'im_text' ? $likeImText : $likeAnfang,
            ]);

            foreach ($stmt->fetchAll() as $row) {
                $wert = trim((string) ($row['wert'] ?? ''));
                if ($wert === '') {
                    continue;
                }

                $schluessel = $abfrage['typ'] . '|' . strtolower($wert);
                if (isset($vorschlaege[$schluessel])) {
                    continue;
                }

                $vorschlaege[$schluessel] = [
                    'typ' => $abfrage['typ'],
                    'wert' => $wert,
                    'anzeige' => (string) ($row['anzeige'] ?? $wert),
                ];
            }
        }

        return array_slice(array_values($vorschlaege), 0, 8);
    }

    public function sucheLokal(string $term, int $start = 0, int $anzahl = 4): array
    {
        $sql = "SELECT DISTINCT films.*
                FROM films
                LEFT JOIN films_genres ON films_genres.films_id = films.id
                LEFT JOIN genres ON genres.id = films_genres.genres_id
                LEFT JOIN films_actors ON films_actors.films_id = films.id
                LEFT JOIN actors ON actors.id = films_actors.actors_id
                LEFT JOIN directors ON directors.id = films.directors_id
                LEFT JOIN languages ON languages.id = films.languages_id
                LEFT JOIN countries ON countries.id = films.countries_id
                WHERE films.title LIKE :term
                   OR films.year LIKE :term
                   OR films.imdb_id LIKE :term
                   OR genres.genre LIKE :term
                   OR actors.actor LIKE :term
                   OR directors.director LIKE :term
                   OR languages.language LIKE :term
                   OR countries.country LIKE :term
                ORDER BY films.id DESC
                LIMIT :start, :anzahl";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':term', '%' . $term . '%');
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':anzahl', $anzahl, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countSucheLokal(string $term): int
    {
        $sql = "SELECT COUNT(DISTINCT films.id)
                FROM films
                LEFT JOIN films_genres ON films_genres.films_id = films.id
                LEFT JOIN genres ON genres.id = films_genres.genres_id
                LEFT JOIN films_actors ON films_actors.films_id = films.id
                LEFT JOIN actors ON actors.id = films_actors.actors_id
                LEFT JOIN directors ON directors.id = films.directors_id
                LEFT JOIN languages ON languages.id = films.languages_id
                LEFT JOIN countries ON countries.id = films.countries_id
                WHERE films.title LIKE :term
                   OR films.year LIKE :term
                   OR films.imdb_id LIKE :term
                   OR genres.genre LIKE :term
                   OR actors.actor LIKE :term
                   OR directors.director LIKE :term
                   OR languages.language LIKE :term
                   OR countries.country LIKE :term";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':term' => '%' . $term . '%']);
        return (int) $stmt->fetchColumn();
    }

    public function exportDaten(): array
    {
        // Der Export soll nicht nur technische IDs liefern.
        // Deshalb werden dieselben lesbaren Werte ausgegeben, die auch in der Einzelansicht wichtig sind.
        $sql = "SELECT
                    films.id,
                    films.imdb_id,
                    films.title,
                    films.year,
                    films.runtime,
                    directors.director,
                    languages.language,
                    countries.country,
                    films.poster,
                    films.plot,
                    GROUP_CONCAT(DISTINCT actors.actor SEPARATOR ', ') AS actors,
                    GROUP_CONCAT(DISTINCT genres.genre SEPARATOR ', ') AS genres,
                    films.created_at
                FROM films
                LEFT JOIN directors ON directors.id = films.directors_id
                LEFT JOIN languages ON languages.id = films.languages_id
                LEFT JOIN countries ON countries.id = films.countries_id
                LEFT JOIN films_actors ON films_actors.films_id = films.id
                LEFT JOIN actors ON actors.id = films_actors.actors_id
                LEFT JOIN films_genres ON films_genres.films_id = films.id
                LEFT JOIN genres ON genres.id = films_genres.genres_id
                GROUP BY
                    films.id,
                    films.imdb_id,
                    films.title,
                    films.year,
                    films.runtime,
                    directors.director,
                    languages.language,
                    countries.country,
                    films.poster,
                    films.plot,
                    films.created_at
                ORDER BY films.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function filmStatistiken(): array
    {
        return [
            'summen' => [
                'filme' => $this->countTabelle('films'),
                'actors' => $this->countTabelle('actors'),
                'genres' => $this->countTabelle('genres'),
                'directors' => $this->countTabelle('directors'),
                'languages' => $this->countTabelle('languages'),
                'countries' => $this->countTabelle('countries'),
                'filme_mit_kommentaren' => $this->countDistinct('comments', 'films_id'),
            ],
            'top' => [
                'actor' => $this->topAusRelation('actors', 'actor', 'films_actors', 'actors_id'),
                'genre' => $this->topAusRelation('genres', 'genre', 'films_genres', 'genres_id'),
                'director' => $this->topAusNachschlagetabelle('directors', 'director', 'directors_id'),
                'language' => $this->topAusNachschlagetabelle('languages', 'language', 'languages_id'),
                'country' => $this->topAusNachschlagetabelle('countries', 'country', 'countries_id'),
                'year' => $this->topAusFilms('year'),
            ],
        ];
    }

    private function countTabelle(string $tabelle): int
    {
        // Mehrere Karten in der Filmstatistik brauchen nur eine einfache Anzahl.
        // Damit diese Zählabfrage nicht immer wieder neu geschrieben wird, bekommt diese Methode den festen Tabellennamen aus dem Projektcode.
        // Aus Formularen oder URLs darf hier nichts kommen, weil Tabellennamen nicht wie normale Werte per PDO-Platzhalter gebunden werden können.
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$tabelle}");
        return (int) $stmt->fetchColumn();
    }

    private function countDistinct(string $tabelle, string $feld): int
    {
        $stmt = $this->db->query("SELECT COUNT(DISTINCT {$feld}) FROM {$tabelle}");
        return (int) $stmt->fetchColumn();
    }

    private function topAusRelation(string $tabelle, string $wertFeld, string $relation, string $zielFeld): array
    {
        // Actors und Genres hängen über Zwischentabellen an den Filmen.
        // COUNT() zeigt, welcher Wert in den gespeicherten Filmen am häufigsten vorkommt.
        $sql = "SELECT {$tabelle}.{$wertFeld} AS wert, COUNT({$relation}.films_id) AS anzahl
                FROM {$relation}
                JOIN {$tabelle} ON {$tabelle}.id = {$relation}.{$zielFeld}
                GROUP BY {$tabelle}.id, {$tabelle}.{$wertFeld}
                ORDER BY anzahl DESC, {$tabelle}.{$wertFeld} ASC
                LIMIT 1";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row ?: ['wert' => '-', 'anzahl' => 0];
    }

    private function topAusNachschlagetabelle(string $tabelle, string $wertFeld, string $filmFremdschluessel): array
    {
        $sql = "SELECT {$tabelle}.{$wertFeld} AS wert, COUNT(films.id) AS anzahl
                FROM films
                JOIN {$tabelle} ON {$tabelle}.id = films.{$filmFremdschluessel}
                GROUP BY {$tabelle}.id, {$tabelle}.{$wertFeld}
                ORDER BY anzahl DESC, {$tabelle}.{$wertFeld} ASC
                LIMIT 1";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row ?: ['wert' => '-', 'anzahl' => 0];
    }

    private function topAusFilms(string $feld): array
    {
        $sql = "SELECT {$feld} AS wert, COUNT(id) AS anzahl
                FROM films
                WHERE {$feld} IS NOT NULL AND {$feld} <> ''
                GROUP BY {$feld}
                ORDER BY anzahl DESC, {$feld} ASC
                LIMIT 1";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch();
        return $row ?: ['wert' => '-', 'anzahl' => 0];
    }
}
