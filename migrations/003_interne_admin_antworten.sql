USE gruppe2;

-- Entwicklungsschritt: interne Antworten des Admins auf Kontaktanfragen.
-- Die Antwort bleibt im System und wird dem Benutzer nach dem Login angezeigt.
-- create_database.sql enthält den aktuellen Endstand bereits; diese Datei dokumentiert den Zwischenschritt.
ALTER TABLE messages
    ADD COLUMN answer TEXT NULL AFTER message,
    ADD COLUMN answered_at DATETIME NULL AFTER created_at;
