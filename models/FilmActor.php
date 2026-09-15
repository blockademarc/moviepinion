<?php
require_once __DIR__ . '/aModel.php';

// Diese Zwischentabelle verbindet Filme mit Schauspielern. Ein Film kann viele Schauspieler haben, ein Schauspieler kann in vielen Filmen vorkommen.
class FilmActor extends aModel
{
    private int $films_id = 0;
    private int $actors_id = 0;

    public function setFilmsId(mixed $wert): void
    {
        $this->films_id = (int) $wert;
        $this->values['films_id'] = $this->films_id;
    }

    public function setActorsId(mixed $wert): void
    {
        $this->actors_id = (int) $wert;
        $this->values['actors_id'] = $this->actors_id;
    }

    public function insert()
    {
        $sql = "INSERT IGNORE INTO films_actors (films_id, actors_id) VALUES (:films_id, :actors_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':films_id' => $this->films_id,
            ':actors_id' => $this->actors_id,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function insertVerbindung(int $filmId, int $zielId): void
    {
        $this->setFilmsId($filmId);
        $this->setActorsId($zielId);
        $this->insert();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM films_actors WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM films_actors");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
