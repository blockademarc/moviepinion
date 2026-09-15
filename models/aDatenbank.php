<?php
require_once __DIR__ . '/../includes/config.php';

// Diese abstrakte Klasse kümmert sich nur um die Datenbankverbindung.
// Die fachlichen Tabellenmethoden gehören in die einzelnen Models, weil nicht jede Tabelle dieselben Aufgaben hat.
abstract class aDatenbank
{
    private string $host = DB_HOST;
    private string $db_name = DB_NAME;
    private string $username = DB_USER;
    private string $password = DB_PASS;

    protected ?PDO $db = null;

    public function dbVerbindung(): PDO
    {
        // Die Verbindung wird nur einmal aufgebaut und danach wiederverwendet.
        if ($this->db instanceof PDO) {
            return $this->db;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

        try {
            // PDO kann z. B. wegen falschem Datenbanknamen, falschem Benutzer oder nicht laufendem MySQL scheitern.
            // Genau solche Stellen werden abgefangen und verständlich weitergegeben.
            $this->db = new PDO($dsn, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            // Die technische Fehlermeldung wird nicht direkt im Frontend ausgegeben.
            // Die eigentliche technische Meldung bleibt aus der Browserausgabe heraus.
            throw new RuntimeException('Die Datenbankverbindung konnte nicht hergestellt werden. Bitte MySQL und die Datenbank gruppe2 prüfen.');
        }

        return $this->db;
    }
}
