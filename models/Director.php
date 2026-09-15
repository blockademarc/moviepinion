<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/NachschlageTrait.php';

// Regisseure werden als ganze OMDb-Angabe gespeichert und von mehreren Filmen wiederverwendet.
class Director extends aModel
{
    use NachschlageTrait;

    private ?int $id = null;
    private string $director = '';

    public function setId(mixed $id): void
    {
        $this->id = $id !== null ? (int) $id : null;
        $this->values['id'] = $this->id;
    }

    public function setDirector(mixed $director): void
    {
        $this->director = trim((string) $director);
        $this->values['director'] = $this->director;
    }

    public function insert()
    {
        $sql = "INSERT INTO directors (director) VALUES (:director)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':director' => $this->director]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM directors WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM directors ORDER BY director");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByValue(string $wert): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM directors WHERE director = :wert");
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
        $this->setDirector($wert);
        return $this->insert();
    }
}
