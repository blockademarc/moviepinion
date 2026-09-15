<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/NachschlageTrait.php';

// Länderangaben werden als ganze OMDb-Angabe gespeichert, z. B. „United States, United Kingdom“.
class Country extends aModel
{
    use NachschlageTrait;

    private ?int $id = null;
    private string $country = '';

    public function setId(mixed $id): void
    {
        $this->id = $id !== null ? (int) $id : null;
        $this->values['id'] = $this->id;
    }

    public function setCountry(mixed $country): void
    {
        $this->country = trim((string) $country);
        $this->values['country'] = $this->country;
    }

    public function insert()
    {
        $sql = "INSERT INTO countries (country) VALUES (:country)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':country' => $this->country]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM countries WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM countries ORDER BY country");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByValue(string $wert): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM countries WHERE country = :wert");
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
        $this->setCountry($wert);
        return $this->insert();
    }
}
