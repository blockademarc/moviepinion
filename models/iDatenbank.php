<?php
// Alle Modelklassen, die direkt zu einer Tabelle gehören, können Datensätze anlegen und lesen.
// Ein allgemeines update() gibt es hier nicht, weil OMDb-Filmdaten nach dem Import nicht bearbeitet werden.
interface iDatenbank
{
    public function insert();
    public function select($id);
    public function selectAll();
}
