<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/NachschlageTrait.php';

// Actors werden getrennt gespeichert, weil ein Schauspieler in mehreren Filmen vorkommen kann.
class Actor extends aModel
{
    use NachschlageTrait;

    private ?int $id = null;
    private string $actor = '';

    public function setId(mixed $id): void
    {
        $this->id = $id !== null ? (int) $id : null;
        $this->values['id'] = $this->id;
    }

    public function setActor(mixed $actor): void
    {
        $this->actor = trim((string) $actor);
        $this->values['actor'] = $this->actor;
    }

    public function insert()
    {
        $sql = "INSERT INTO actors (actor) VALUES (:actor)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':actor' => $this->actor]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM actors WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM actors ORDER BY actor");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByValue(string $wert): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM actors WHERE actor = :wert");
        $stmt->execute([':wert' => trim($wert)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insertOrSelectId(string $wert): ?int
    {
        $wert = $this->bereinigeNachschlagewert($wert);
        if ($wert === null) {
            return null;
        }

        $vorhanden = $this->selectByValue($wert);
        if ($vorhanden) {
            return (int) $vorhanden['id'];
        }

        // Wenn der Wert neu ist, wird er einmal angelegt. Danach verwenden die Filme nur noch seine ID.
        $this->setActor($wert);
        return $this->insert();
    }
}
