-- Ersteinrichtung: Die Datenbank gruppe2 darf noch nicht existieren.
-- Bestehende Datenbanken werden von diesem Skript nicht gelöscht.
CREATE DATABASE gruppe2 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE gruppe2;

-- Registrierte Benutzer und der feste Admin-Zugang.
CREATE TABLE users
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    alias VARCHAR(60) UNIQUE NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    passwort VARCHAR(255) NOT NULL,
    rolle VARCHAR(20) NOT NULL DEFAULT 'benutzer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kleine Nachschlagetabelle: ein Regisseur-Wert kann bei mehreren Filmen vorkommen.
CREATE TABLE directors
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    director VARCHAR(255) UNIQUE NOT NULL
);

-- Kleine Nachschlagetabelle: Sprachangaben bleiben als komplette OMDb-Angabe erhalten.
CREATE TABLE languages
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    language VARCHAR(255) UNIQUE NOT NULL
);

-- Kleine Nachschlagetabelle: Länderangaben bleiben als komplette OMDb-Angabe erhalten.
CREATE TABLE countries
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    country VARCHAR(255) UNIQUE NOT NULL
);

-- Zentrale Filmtabelle mit den direkten Filmdaten aus OMDb.
CREATE TABLE films
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    imdb_id VARCHAR(30) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    year VARCHAR(30),
    runtime VARCHAR(80),
    directors_id INT NULL,
    languages_id INT NULL,
    countries_id INT NULL,
    plot TEXT,
    poster VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (directors_id) REFERENCES directors(id) ON DELETE SET NULL,
    FOREIGN KEY (languages_id) REFERENCES languages(id) ON DELETE SET NULL,
    FOREIGN KEY (countries_id) REFERENCES countries(id) ON DELETE SET NULL
);

-- Genres werden getrennt gespeichert, weil ein Genre bei vielen Filmen vorkommen kann.
CREATE TABLE genres
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    genre VARCHAR(120) UNIQUE NOT NULL
);

-- Schauspieler werden getrennt gespeichert, weil ein Schauspieler in vielen Filmen vorkommen kann.
CREATE TABLE actors
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    actor VARCHAR(255) UNIQUE NOT NULL
);

-- Zwischentabelle für die m:n-Beziehung zwischen Filmen und Genres.
CREATE TABLE films_genres
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    films_id INT NOT NULL,
    genres_id INT NOT NULL,
    UNIQUE KEY uq_films_genres (films_id, genres_id),
    FOREIGN KEY (films_id) REFERENCES films(id) ON DELETE CASCADE,
    FOREIGN KEY (genres_id) REFERENCES genres(id) ON DELETE CASCADE
);

-- Zwischentabelle für die m:n-Beziehung zwischen Filmen und Schauspielern.
CREATE TABLE films_actors
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    films_id INT NOT NULL,
    actors_id INT NOT NULL,
    UNIQUE KEY uq_films_actors (films_id, actors_id),
    FOREIGN KEY (films_id) REFERENCES films(id) ON DELETE CASCADE,
    FOREIGN KEY (actors_id) REFERENCES actors(id) ON DELETE CASCADE
);

-- Kommentare gehören zu einem registrierten Benutzer und zu einem Film.
CREATE TABLE comments
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NOT NULL,
    films_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (films_id) REFERENCES films(id) ON DELETE CASCADE
);

-- Interne Anfragen von Benutzern an den Admin, inklusive Antwort im System.
CREATE TABLE messages
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    answer TEXT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'offen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    answered_at DATETIME NULL,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Gespeicherte Originalantworten von OMDb als JSON- oder XML-Dateien.
-- Jede Rohdatei gehört zu einem lokalen Film, weil die Downloadbereiche immer von einem Film ausgehen.
CREATE TABLE api_files
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT NULL,
    films_id INT NOT NULL,
    imdb_id VARCHAR(30) NOT NULL,
    format VARCHAR(10) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY ak_api_files_films_format (films_id, format),
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (films_id) REFERENCES films(id) ON DELETE CASCADE
);

-- Admin-Zugang: alias admin, Passwort admin.
-- Der Hash wurde mit password_hash('admin', PASSWORD_DEFAULT) erzeugt.
INSERT INTO users (alias, email, passwort, rolle)
VALUES ('admin', 'admin@example.test', '$2y$12$koOW2JWq/8NbjP5jTw1Is.K//qz13F2aawHniuV9SsAxDPergkjse', 'admin');
