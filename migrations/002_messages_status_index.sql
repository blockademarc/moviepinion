USE gruppe2;

-- Entwicklungsschritt: Index für interne Kontaktanfragen.
-- Der Admin unterscheidet offene und beantwortete Nachrichten, deshalb wurde der Status später gezielt beschleunigt.
-- create_database.sql enthält den aktuellen Endstand bereits; diese Datei dokumentiert den Zwischenschritt.
ALTER TABLE messages ADD INDEX idx_messages_status (status);
