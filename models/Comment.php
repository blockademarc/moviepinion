<?php
require_once __DIR__ . '/aModel.php';
require_once __DIR__ . '/iLoeschbar.php';

// Kommentare sind die eine Inhaltsart, die der Admin direkt löschen darf.
// Darum implementiert diese Klasse zusätzlich das kleine Lösch-Interface.
class Comment extends aModel implements iLoeschbar
{
    private int $users_id = 0;
    private int $films_id = 0;
    private string $comment = '';

    public function setUsers_id(mixed $id): void { $this->users_id = (int) $id; $this->values['users_id'] = $this->users_id; }
    public function setFilms_id(mixed $id): void { $this->films_id = (int) $id; $this->values['films_id'] = $this->films_id; }
    public function setComment(mixed $text): void { $this->comment = trim((string) $text); $this->values['comment'] = $this->comment; }

    public function insert()
    {
        $sql = "INSERT INTO comments (users_id, films_id, comment) VALUES (:users_id, :films_id, :comment)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':users_id' => $this->users_id, ':films_id' => $this->films_id, ':comment' => $this->comment]);
        return (int) $this->db->lastInsertId();
    }

    public function select($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE id = :id");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch();
    }

    public function selectAll()
    {
        $sql = "SELECT comments.*, users.alias, films.title FROM comments
                JOIN users ON users.id = comments.users_id
                JOIN films ON films.id = comments.films_id
                ORDER BY comments.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectByFilm(int $filmId): array
    {
        $sql = "SELECT comments.*, users.alias FROM comments
                JOIN users ON users.id = comments.users_id
                WHERE comments.films_id = :films_id
                ORDER BY comments.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':films_id' => $filmId]);
        return $stmt->fetchAll();
    }

    public function delete(int $id): void
    {
        // Einzelne Kommentare dürfen im Backend entfernt werden, wenn sie offensichtlich nicht zum Filmportal gehören.
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
