<?php
// Löschen ist nur dort Teil der Projektlogik, wo es wirklich gebraucht wird.
// Der Admin darf Kommentare löschen und Benutzer entfernen; Filmdaten aus OMDb werden nicht gelöscht.
interface iLoeschbar
{
    public function delete(int $id): void;
}
