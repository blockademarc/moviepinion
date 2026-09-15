<?php
require_once __DIR__ . '/aModel.php';

// Diese Zwischentabelle verbindet Filme mit Genres. Ein Film kann mehrere Genres haben, ein Genre kann bei vielen Filmen vorkommen.
class FilmGenre extends aModel
{
    private int $films_id = 0;
    private int $genres_id = 0;

    public function setFilmsId(mixed $wert): void
    {
        $this->films_id = (int) $wert;
        $this->values['films_id'] = $this->films_id;
    }

    public function setGenresId(mixed $wert): void
    {
        $this->genres_id = (int) $wert;
        $this->values['genres_id'] = $this->genres_id;
    }

    public function insert()
    {
        $sql = "INSERT IGNORE INTO films_genres (films_id, genres_id) VALUES (:films_id, :genres_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':films_id' => $this->films_id,
            ':genres_id' => $this->genres_id,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function insertVerbindung(int $filmId, int $zielId): void
    {
        $this->setFilmsId($filmId);
        $this->setGenresId($zielId);
        $this->insert();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM films_genres WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM films_genres");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
