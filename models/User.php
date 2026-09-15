<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/iLoeschbar.php';

// Benutzerkonten steuern den privaten Bereich. Nur angemeldete Benutzer dürfen Filme holen, Kommentare schreiben und Rohdateien herunterladen.
class User extends aModel implements iLoeschbar
{
    private ?int $id = null;
    private string $alias = '';
    private string $email = '';
    private string $passwort = '';
    private string $rolle = 'benutzer';

    public function setId(mixed $id): void { $this->id = $id !== null ? (int) $id : null; $this->values['id'] = $this->id; }
    public function setAlias(mixed $alias): void { $this->alias = trim((string) $alias); $this->values['alias'] = $this->alias; }
    public function setEmail(mixed $email): void { $this->email = trim((string) $email); $this->values['email'] = $this->email; }
    public function setPasswort(mixed $passwort): void { $this->passwort = (string) $passwort; $this->values['passwort'] = $this->passwort; }
    public function setRolle(mixed $rolle): void { $this->rolle = (string) $rolle; $this->values['rolle'] = $this->rolle; }

    public function insert()
    {
        // Passwörter werden immer mit password_hash() gespeichert.
        // In PHP kann password_get_info() je nach Version algo = null liefern;
        // deshalb prüfen wir nicht auf eine Zahl, sondern auf den verständlichen algoName.
        $info = password_get_info($this->passwort);
        $passwortHash = ($info['algoName'] ?? 'unknown') === 'unknown'
            ? password_hash($this->passwort, PASSWORD_DEFAULT)
            : $this->passwort;

        $sql = "INSERT INTO users (alias, email, passwort, rolle) VALUES (:alias, :email, :passwort, :rolle)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':alias' => $this->alias,
            ':email' => $this->email,
            ':passwort' => $passwortHash,
            ':rolle' => $this->rolle,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT id, alias, email, rolle, created_at FROM users WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT id, alias, email, rolle, created_at FROM users ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findeNachAlias(string $alias): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE alias = :alias");
        $stmt->execute([':alias' => trim($alias)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function login(string $alias, string $passwort): ?array
    {
        // Login bleibt minimalistisch: Alias + Passwort.
        // Die Prüfung selbst muss aber korrekt mit password_verify() laufen.
        $user = $this->findeNachAlias($alias);
        if (!$user) {
            return null;
        }

        $gespeichertesPasswort = (string) $user['passwort'];

        if (password_verify($passwort, $gespeichertesPasswort)) {
            // Falls PHP später einen besseren Standard-Algorithmus nutzt, kann der Hash erneuert werden.
            if (password_needs_rehash($gespeichertesPasswort, PASSWORD_DEFAULT)) {
                $this->aktualisierePasswortHash((int) $user['id'], password_hash($passwort, PASSWORD_DEFAULT));
            }
            return $user;
        }

        return null;
    }

    private function aktualisierePasswortHash(int $id, string $hash): void
    {
        $stmt = $this->db->prepare("UPDATE users SET passwort = :passwort WHERE id = :id");
        $stmt->execute([':passwort' => $hash, ':id' => $id]);
    }

    public function delete(int $id): void
    {
        // Beim Löschen eines Benutzers darf kein Kommentar dieses Benutzers im System zurückbleiben.
        // Deshalb wird zuerst die abhängige Tabelle comments bereinigt und danach erst der Benutzer entfernt.
        $stmt = $this->db->prepare("DELETE FROM comments WHERE users_id = :id");
        $stmt->execute([':id' => $id]);

        // Der feste Adminzugang bleibt geschützt.
        // Ein versehentlicher Klick im Backend darf den Administrator nicht aus der Datenbank entfernen.
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id AND rolle <> 'admin'");
        $stmt->execute([':id' => $id]);
    }
}
