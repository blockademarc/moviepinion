<?php
require_once __DIR__ . '/aModel.php';

class Message extends aModel
{
    private ?int $users_id = null;
    private string $name = '';
    private string $email = '';
    private string $subject = '';
    private string $message = '';
    private string $answer = '';
    private string $status = 'offen';

    public function setUsers_id(mixed $id): void { $this->users_id = $id !== null ? (int) $id : null; $this->values['users_id'] = $this->users_id; }
    public function setName(mixed $name): void { $this->name = trim((string) $name); $this->values['name'] = $this->name; }
    public function setEmail(mixed $email): void { $this->email = trim((string) $email); $this->values['email'] = $this->email; }
    public function setSubject(mixed $subject): void { $this->subject = trim((string) $subject); $this->values['subject'] = $this->subject; }
    public function setMessage(mixed $message): void { $this->message = trim((string) $message); $this->values['message'] = $this->message; }
    public function setAnswer(mixed $answer): void { $this->answer = trim((string) $answer); $this->values['answer'] = $this->answer; }
    public function setStatus(mixed $status): void { $this->status = trim((string) $status) ?: 'offen'; $this->values['status'] = $this->status; }

    public function insert()
    {
        // Kontaktanfragen werden lokal gespeichert, damit der Admin sie im Backend sehen kann.
        $sql = "INSERT INTO messages (users_id, name, email, subject, message, status)
                VALUES (:users_id, :name, :email, :subject, :message, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':users_id' => $this->users_id,
            ':name' => $this->name,
            ':email' => $this->email,
            ':subject' => $this->subject,
            ':message' => $this->message,
            ':status' => $this->status,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM messages ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByUser(int $userId): array
    {
        // Der Benutzer sieht nach dem Login seine eigenen Anfragen und die Antwort des Admins.
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE users_id = :users_id ORDER BY created_at DESC");
        $stmt->execute([':users_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function selectAllWithUser(): array
    {
        // Die Anfrage wird mit dem Alias verbunden, wenn sie von einem registrierten Benutzer kommt.
        $sql = "SELECT messages.*, users.alias
                FROM messages
                LEFT JOIN users ON users.id = messages.users_id
                ORDER BY messages.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function answer(int $id, string $answer): void
    {
        // Die Admin-Antwort wird in derselben Tabelle gespeichert.
        // Damit bleibt die Kommunikation sehr einfach: eine Anfrage, eine Antwort, ein Status.
        $stmt = $this->db->prepare("UPDATE messages
            SET answer = :answer, status = 'beantwortet', answered_at = NOW()
            WHERE id = :id");
        $stmt->execute([':answer' => trim($answer), ':id' => $id]);
    }

    public function countAll(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(id) FROM messages");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(id) FROM messages WHERE status = :status");
        $stmt->execute([':status' => $status]);
        return (int) $stmt->fetchColumn();
    }
}
