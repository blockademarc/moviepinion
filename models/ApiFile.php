<?php
require_once __DIR__ . '/aModel.php';

class ApiFile extends aModel
{
    private ?int $users_id = null;
    private ?int $films_id = null;
    private string $imdb_id = '';
    private string $format = 'json';
    private string $file_path = '';

    public function setUsers_id(mixed $id): void { $this->users_id = $id !== null ? (int) $id : null; $this->values['users_id'] = $this->users_id; }
    public function setFilms_id(mixed $id): void { $this->films_id = $id !== null ? (int) $id : null; $this->values['films_id'] = $this->films_id; }
    public function setImdb_id(mixed $id): void { $this->imdb_id = trim((string) $id); $this->values['imdb_id'] = $this->imdb_id; }
    public function setFormat(mixed $format): void { $this->format = strtolower(trim((string) $format)); $this->values['format'] = $this->format; }
    public function setFile_path(mixed $pfad): void { $this->file_path = trim((string) $pfad); $this->values['file_path'] = $this->file_path; }

    public function insert()
    {
        // Jede gespeicherte Rohdatei gehört zu einem Film, der bereits in der lokalen Datenbank steht.
        // Ohne diese Film-ID gäbe es später keinen sinnvollen Bezug für den Downloadbereich in Galerie und Einzelansicht.
        if ($this->films_id === null || $this->films_id <= 0) {
            throw new RuntimeException('Die OMDb-Rohdatei kann keinem lokalen Film zugeordnet werden.');
        }

        // Zu einem Film brauchen wir pro Format nur eine Rohdatei.
        // Wenn der gleiche Film später noch einmal über OMDb geholt wird,
        // wird die JSON- oder XML-Datei im daten-Ordner überschrieben.
        // Der Datenbankeintrag muss dann nicht doppelt entstehen, weil die Galerie
        // und die Einzelansicht nur wissen müssen: Zu diesem Film gibt es JSON oder XML.
        $vorhanden = $this->selectByFilmAndFormat($this->films_id, $this->format);

        if ($vorhanden) {
			// Bei erneutem Import auf die aktuell gespeicherte Datei verweisen.
			$stmt = $this->db->prepare("UPDATE api_files SET file_path = :file_path WHERE id = :id");
			$stmt->execute([':file_path' => $this->file_path, ':id' => (int) $vorhanden['id'],]);
			return (int) $vorhanden['id'];
		}

        $sql = "INSERT INTO api_files (users_id, films_id, imdb_id, format, file_path) VALUES (:users_id, :films_id, :imdb_id, :format, :file_path)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':users_id' => $this->users_id,
            ':films_id' => $this->films_id,
            ':imdb_id' => $this->imdb_id,
            ':format' => $this->format,
            ':file_path' => $this->file_path,
        ]);
        return (int) $this->db->lastInsertId();
    }

    private function selectByFilmAndFormat(?int $filmId, string $format): ?array
    {
        // Diese Prüfung verhindert doppelte Metadatensätze zu derselben Rohdatei-Art.
        // Ohne sie könnte ein wiederholter OMDb-Abruf denselben Film mehrfach in api_files eintragen,
        // obwohl die eigentliche Film-Datenbank durch die IMDb-ID bereits vor Dopplungen geschützt ist.
        if ($filmId === null || $filmId <= 0) {
            return null;
        }

        $format = strtolower(trim($format)) === 'xml' ? 'xml' : 'json';

        $stmt = $this->db->prepare("SELECT * FROM api_files WHERE films_id = :films_id AND format = :format LIMIT 1");
        $stmt->execute([
            ':films_id' => $filmId,
            ':format' => $format,
        ]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function select($id)
    {
        // Download-Links arbeiten nur mit der ID aus api_files.
        // Der echte Dateipfad bleibt dadurch auf dem Server und steht nicht direkt in der URL.
        $stmt = $this->db->prepare("SELECT * FROM api_files WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM api_files ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByFormat(string $format): int
    {
        // Die Systemstatistik zeigt getrennt, wie viele JSON- und XML-Rohdateien gespeichert wurden.
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM api_files WHERE format = :format");
        $stmt->execute([':format' => strtolower($format)]);
        return (int) $stmt->fetchColumn();
    }

    public function realerDownloadPfad(array $apiFile): ?string
    {
        // Beim Speichern einer OMDb-Antwort wird in der Datenbank nur ein Projektpfad abgelegt,
        // zum Beispiel daten/json/the_thing.json oder daten/xml/the_thing.xml.
        // Dadurch hängt der Datensatz nicht davon ab, ob das Projekt unter Windows, Linux oder auf einem anderen Server läuft.
        $gespeicherterPfad = trim((string) ($apiFile['file_path'] ?? ''));
        $gespeicherterPfad = str_replace('\\', '/', $gespeicherterPfad);
        $gespeicherterPfad = ltrim($gespeicherterPfad, '/');

        if ($gespeicherterPfad === '') {
            return null;
        }

        // Für neue Projektdaten gibt es nur zwei erlaubte Speicherorte für Rohdateien.
        // Alles andere wird abgelehnt, weil die Download-Funktion keine beliebigen Serverdateien ausliefern soll.
        $liegtImJsonOrdner = substr($gespeicherterPfad, 0, strlen('daten/json/')) === 'daten/json/';
        $liegtImXmlOrdner = substr($gespeicherterPfad, 0, strlen('daten/xml/')) === 'daten/xml/';

        if (!$liegtImJsonOrdner && !$liegtImXmlOrdner) {
            return null;
        }

        // Pfade mit .. würden aus dem erlaubten Ordner herausführen können.
        // Solche Pfade werden nicht benötigt, weil unsere Anwendung die Dateinamen selbst erzeugt.
        if (strpos($gespeicherterPfad, '../') !== false || strpos($gespeicherterPfad, "\0") !== false) {
            return null;
        }

        $realerPfad = realpath(PROJEKT_PFAD . '/' . $gespeicherterPfad);
        $datenPfad = realpath(PROJEKT_PFAD . '/daten');

        if ($realerPfad === false || $datenPfad === false || !is_file($realerPfad)) {
            return null;
        }

        // realpath() liefert den tatsächlichen Pfad auf dem Server.
        // Erst danach prüfen wir, ob die Datei wirklich im daten-Ordner des Projekts liegt.
        // Diese Prüfung bleibt unabhängig vom Betriebssystem, weil beide Pfade vorher vereinheitlicht werden.
        $realerPfadNormiert = rtrim(str_replace('\\', '/', $realerPfad), '/');
        $datenPfadNormiert = rtrim(str_replace('\\', '/', $datenPfad), '/');

        if ($realerPfadNormiert !== $datenPfadNormiert
            && substr($realerPfadNormiert, 0, strlen($datenPfadNormiert) + 1) !== $datenPfadNormiert . '/') {
            return null;
        }

        return $realerPfad;
    }

    public function downloadsByFilmIds(array $filmIds): array
    {
        // Die Galerie kann mehrere Treffer anzeigen.
        // Hier holen wir alle passenden Rohdateien in einer Abfrage, damit nicht für jede Filmkarte einzeln gesucht wird.
        $filmIds = array_values(array_unique(array_map('intval', $filmIds)));
        $filmIds = array_filter($filmIds, fn($id) => $id > 0);

        if (empty($filmIds)) {
            return [];
        }

        $platzhalter = implode(',', array_fill(0, count($filmIds), '?'));
        $stmt = $this->db->prepare("SELECT id, films_id, format, file_path FROM api_files WHERE films_id IN ({$platzhalter}) ORDER BY films_id, format");
        $stmt->execute($filmIds);

        $dateien = [];
        foreach ($stmt->fetchAll() as $row) {
            $filmId = (int) ($row['films_id'] ?? 0);
            $format = strtolower((string) ($row['format'] ?? ''));

            if ($filmId <= 0 || ($format !== 'json' && $format !== 'xml')) {
                continue;
            }

            if ($this->realerDownloadPfad($row) === null) {
                continue;
            }

            if (!isset($dateien[$filmId])) {
                $dateien[$filmId] = [];
            }

            // Die Datenbank erlaubt pro Film und Format nur einen Eintrag.
            // Die zusätzliche Prüfung hält die Ausgabe trotzdem stabil, falls später Daten manuell geändert wurden.
            if (!isset($dateien[$filmId][$format])) {
                $dateien[$filmId][$format] = [
                    'id' => (int) $row['id'],
                    'format' => $format,
                ];
            }
        }

        return $dateien;
    }

    public function downloadsByFilm(int $filmId): array
    {
        $dateien = $this->downloadsByFilmIds([$filmId]);
        return $dateien[$filmId] ?? [];
    }
}
