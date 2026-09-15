<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/NachschlageTrait.php';

// Genres werden getrennt gespeichert, weil ein Genre bei mehreren Filmen vorkommen kann.
class Genre extends aModel
{
    use NachschlageTrait;

    private ?int $id = null;
    private string $genre = '';

    public function setId(mixed $id): void
    {
        $this->id = $id !== null ? (int) $id : null;
        $this->values['id'] = $this->id;
    }

    public function setGenre(mixed $genre): void
    {
        $this->genre = trim((string) $genre);
        $this->values['genre'] = $this->genre;
    }

    public function insert()
    {
        $sql = "INSERT INTO genres (genre) VALUES (:genre)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':genre' => $this->genre]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM genres WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM genres ORDER BY genre");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByValue(string $wert): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM genres WHERE genre = :wert");
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
        $this->setGenre($wert);
        return $this->insert();
    }
}
