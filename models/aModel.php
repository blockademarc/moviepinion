<?php
require_once __DIR__ . '/aDatenbank.php';
require_once __DIR__ . '/iDatenbank.php';

// Alle Tabellenklassen brauchen dieselbe Grundarbeit:
// Daten aus Formularen oder OMDb werden übernommen, die Datenbankverbindung wird bereitgestellt,
// und gesetzte Werte bleiben im Objekt nachvollziehbar gespeichert.
abstract class aModel extends aDatenbank implements iDatenbank
{
    protected array $values = [];

    public function __construct(array $param = [])
    {
        // Jedes Model arbeitet mit der gleichen Datenbankverbindung.
        // Danach werden übergebene Werte sofort über die passenden Setter in das Objekt übernommen.
        $this->db = $this->dbVerbindung();
        $this->setDaten($param);
    }

    public function __destruct()
    {
        // Ein Model lebt in dieser Anwendung nur für den aktuellen Seitenaufruf.
        // Am Ende wird die interne Werteliste geleert, damit keine alten Objektwerte weitergetragen werden.
        $this->values = [];
    }

    public function setDaten(array $daten): void
    {
        foreach ($daten as $key => $value) {
            $setter = 'set' . ucfirst((string) $key);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                // Nicht jedes OMDb-Feld wird als eigene Eigenschaft gebraucht.
                // Werte ohne eigenen Setter bleiben trotzdem im Objekt sichtbar und gehen nicht still verloren.
                $this->values[$key] = $value;
            }
        }
    }

    public function __get(string $key): mixed
    {
        // Einige Views lesen einfache Werte direkt aus dem Objektzustand.
        // __get() erlaubt diesen Zugriff, ohne für jeden nur mitgeführten Wert eine eigene Methode zu schreiben.
        return $this->values[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        // Werte, die nicht über einen eigenen Setter laufen, landen kontrolliert in derselben internen Liste.
        // Dadurch bleibt der Objektzustand auch bei zusätzlichen OMDb-Werten nachvollziehbar.
        $this->values[$key] = $value;
    }

    public function __toString(): string
    {
        // Falls ein Model als Text ausgegeben wird, zeigt diese Methode die aktuell gesetzten Werte.
        // Das ist beim Nachvollziehen eines Objekts hilfreich, ohne direkt auf seine Eigenschaften zuzugreifen.
        // Die Ausgabe wird maskiert, damit gespeicherte Texte nicht ungeprüft als HTML im Browser landen.
        $daten = '';
        foreach ($this->values as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            $daten .= htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8')
                . ': '
                . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8')
                . '<br>';
        }

        return $daten;
    }
}
