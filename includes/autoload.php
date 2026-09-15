<?php
// Einfacher Autoloader für die Projektklassen.
// Die Tabellen-Models liegen in models/. Kleine Hilfsklassen ohne eigene Tabelle liegen in includes/.
spl_autoload_register(function ($klasse) {
    $pfade = [
        __DIR__ . '/../models/' . $klasse . '.php',
        __DIR__ . '/' . $klasse . '.php',
    ];

    foreach ($pfade as $datei) {
        if (file_exists($datei)) {
            require_once $datei;
            return;
        }
    }
});
