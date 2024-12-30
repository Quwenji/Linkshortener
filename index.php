<?php
// index.php

// Fehleranzeige aktivieren (nur zu Entwicklungszwecken)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Controller einbinden (enthält Funktionen zum Verkürzen und Weiterleiten)
require_once __DIR__ . '/ShortenerController.php';

// Header einbinden (öffnet <html>, <head>, <body>, <header>, <main>)
require_once __DIR__ . '/header.php';

// Routing-Informationen
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Query-String entfernen (z.B. /abc123?foo=bar -> /abc123)
if (strpos($requestUri, '?') !== false) {
    $requestUri = strstr($requestUri, '?', true);
}

// Pfad bereinigen (ohne führenden/trailenden Slash)
$path = trim($requestUri, '/');

// Routing-Logik
if ($requestMethod === 'GET' && $path === '') {
    // Startseite anzeigen (Formular)
    require_once __DIR__ . '/templates/form.php';
} elseif ($requestMethod === 'POST' && $path === 'shorten') {
    // URL verkürzen
    shortenUrl();
} elseif ($requestMethod === 'GET' && $path !== '') {
    // Weiterleitung
    redirectUrl($path);
} else {
    // 404 Fehlerseite
    http_response_code(404);
    ?>
    <div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
        <p class='font-semibold'>Fehler:</p>
        <p>Seite nicht gefunden.</p>
    </div>
    <?php
}

// Footer einbinden (schließt <main>, fügt Footer hinzu, schließt <body> und <html>)
require_once __DIR__ . '/footer.php';
?>
