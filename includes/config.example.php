<?php
// Beispiel für die lokale Projektkonfiguration.
// Diese Datei als config.php kopieren und dort die eigenen Zugangsdaten eintragen.
// includes/config.php darf nicht in das Git-Repository aufgenommen werden.

// Lokale MySQL-/MariaDB-Verbindung.
define('DB_HOST', 'localhost');
define('DB_NAME', 'gruppe2');
define('DB_USER', 'root');
define('DB_PASS', '');

// Persönlichen API-Key unter https://www.omdbapi.com/apikey.aspx anfordern.
define('OMDB_API_KEY', 'BITTE_API_KEY_HIER_EINTRAGEN');

// Projektinterne Verzeichnisse.
define('PROJEKT_PFAD', dirname(__DIR__));
define('DATEN_JSON_PFAD', PROJEKT_PFAD . '/daten/json');
define('DATEN_XML_PFAD', PROJEKT_PFAD . '/daten/xml');
define('EXPORT_JSON_PFAD', PROJEKT_PFAD . '/exports/json');
define('EXPORT_XML_PFAD', PROJEKT_PFAD . '/exports/xml');
define('EXPORT_CSV_PFAD', PROJEKT_PFAD . '/exports/csv');
define('BACKUP_PFAD', PROJEKT_PFAD . '/backups');
