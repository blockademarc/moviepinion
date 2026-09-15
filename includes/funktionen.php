<?php
// Gemeinsame Hilfsfunktionen für Frontend und Backend.

function sichereAusgabe(mixed $wert): string
{
    // Browserausgaben werden mit htmlspecialchars geschützt.
    return htmlspecialchars((string) $wert, ENT_QUOTES, 'UTF-8');
}

function textMitZeilenumbruechen(mixed $wert): string
{
    // Plot und Kommentare dürfen Zeilenumbrüche behalten, aber HTML wird nicht ungefiltert ausgegeben.
    return nl2br(sichereAusgabe($wert));
}

function setzeMeldung(string $text): void
{
    $_SESSION['meldung'] = $text;
}

function setzeFehler(string $text): void
{
    $_SESSION['fehler'] = $text;
}

function holeMeldung(): ?string
{
    $meldung = $_SESSION['meldung'] ?? null;
    unset($_SESSION['meldung']);
    return $meldung;
}

function holeFehler(): ?string
{
    $fehler = $_SESSION['fehler'] ?? null;
    unset($_SESSION['fehler']);
    return $fehler;
}

function weiterleiten(string $ziel): never
{
    // Nach Datenbankaktionen wird umgeleitet, damit ein Reload nicht erneut speichert.
    header('Location: ' . $ziel);
    exit;
}

function istEingeloggt(): bool
{
    return isset($_SESSION['users_id']);
}

function istAdmin(): bool
{
    return isset($_SESSION['rolle']) && $_SESSION['rolle'] === 'admin';
}

function aktuelleUserId(): ?int
{
    return isset($_SESSION['users_id']) ? (int) $_SESSION['users_id'] : null;
}

function verlangeLogin(): void
{
    if (!istEingeloggt()) {
        setzeFehler('Bitte zuerst anmelden.');
        weiterleiten('index.php?action=login');
    }
}

function verlangeAdmin(): void
{
    if (!istAdmin()) {
        setzeFehler('Der Adminbereich ist nur für Administratoren zugänglich.');
        weiterleiten('index.php?action=login');
    }
}

function postWert(string $name): string
{
    return isset($_POST[$name]) ? trim((string) $_POST[$name]) : '';
}

function getWert(string $name): string
{
    return isset($_GET[$name]) ? trim((string) $_GET[$name]) : '';
}

function zerlegeListe(?string $text): array
{
    // OMDb liefert Actors und Genre als kommaseparierte Listen.
    // Director, Language und Country werden im Projekt nicht mit dieser Funktion zerlegt.
    $teile = explode(',', (string) $text);
    $werte = [];

    foreach ($teile as $teil) {
        $wert = trim($teil);
        if ($wert !== '' && strtolower($wert) !== 'n/a') {
            $werte[] = $wert;
        }
    }

    return array_values(array_unique($werte));
}

function erstelleOrdnerWennNoetig(string $pfad): void
{
    if (!is_dir($pfad)) {
        mkdir($pfad, 0777, true);
    }
}

function dateinameAusImdbId(string $imdbId, string $format): string
{
    // Die IMDb-ID ist eindeutiger als ein Filmtitel und eignet sich deshalb als Dateiname. Jedoch....
    $basis = preg_replace('/[^a-zA-Z0-9_-]/', '_', $imdbId);
    return $basis . '.' . strtolower($format);
}

function dateinameAusTitel(string $titel, string $format): string
{
    // ...... Für die gespeicherten Rohdateien sollen lesbare Dateinamen aus den Filmtiteln entstehen.
    // Beispiel: The Godfather wird zu the_godfather.json oder the_godfather.xml.
    $basis = strtolower(trim($titel));
    $basis = preg_replace('/[^a-z0-9]+/i', '_', $basis);
    $basis = trim((string) $basis, '_');

    if ($basis === '') {
        $basis = 'film_' . date('Ymd_His');
    }

    return $basis . '.' . strtolower($format);
}

function findeMysqldump(): string
{
    // Das Projekt kann auf jedem Rechner anders tief unter htdocs liegen.
    // Deshalb gehen wir vom Projektordner aus so lange nach oben,
    // bis wir den XAMPP-Ordner mit mysql/bin/mysqldump.exe finden.
    // Wenn nichts gefunden wird, verwenden wir als letzte Möglichkeit den normalen Befehl mysqldump.

    $ordner = realpath(PROJEKT_PFAD);

    while ($ordner !== false && $ordner !== dirname($ordner)) {
        $kandidat = $ordner . '/mysql/bin/mysqldump.exe';

        if (is_file($kandidat)) {
            return $kandidat;
        }

        $ordner = dirname($ordner);
    }

    return 'mysqldump';
}