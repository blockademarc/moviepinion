<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/NachschlageTrait.php';

// Sprachangaben werden als ganze OMDb-Angabe gespeichert, z. B. „English, Norwegian“.
class Language extends aModel
{
    use NachschlageTrait;

    private ?int $id = null;
    private string $language = '';

    public function setId(mixed $id): void
    {
        $this->id = $id !== null ? (int) $id : null;
        $this->values['id'] = $this->id;
    }

    public function setLanguage(mixed $language): void
    {
        $this->language = trim((string) $language);
        $this->values['language'] = $this->language;
    }

    public function insert()
    {
        $sql = "INSERT INTO languages (language) VALUES (:language)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':language' => $this->language]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM languages WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM languages ORDER BY language");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByValue(string $wert): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM languages WHERE language = :wert");
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
        $this->setLanguage($wert);
        return $this->insert();
    }
}
