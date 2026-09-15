USE gruppe2;

-- Entwicklungsschritt: pro Film und Format soll es nur einen Rohdatei-Eintrag geben.
-- Dadurch bleibt api_files eine Liste vorhandener Dateien und wird nicht zu einer Abruf-Historie.
-- create_database.sql enthält den aktuellen Endstand bereits; diese Datei dokumentiert den Zwischenschritt.
ALTER TABLE api_files ADD UNIQUE KEY ak_api_files_films_format (films_id, format);
