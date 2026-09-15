USE gruppe2;

-- Entwicklungsschritt: Index für die gespeicherten OMDb-Rohdateien.
-- create_database.sql enthält den aktuellen Endstand bereits; diese Datei dokumentiert den Zwischenschritt.
ALTER TABLE api_files ADD INDEX idx_api_files_format (format);
