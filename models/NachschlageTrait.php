<?php
trait NachschlageTrait
{
    protected function bereinigeNachschlagewert(string $wert): ?string
    {
        // OMDb liefert leere Felder oder N/A, wenn eine Angabe nicht vorhanden ist.
        // Solche Werte sollen nicht als eigener Actor, Genre oder Country in die kleinen Nachschlagetabellen wandern.
        $wert = trim($wert);
        if ($wert === '' || strtolower($wert) === 'n/a') {
            return null;
        }

        return $wert;
    }
}
