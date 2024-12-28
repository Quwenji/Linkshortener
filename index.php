<?php
// index.php

// Wir binden nur den Controller ein. Der Controller bindet selbst database.php.
require_once __DIR__ . '/shortenerController.php';

// Request-URI ermitteln
$requestUri = $_SERVER['REQUEST_URI'];   // z.B. "/shorten" oder "/abc123"
$requestMethod = $_SERVER['REQUEST_METHOD']; // GET oder POST

// Query-String abschneiden? ("/abc123?foo=bar" -> "/abc123")
if (strpos($requestUri, '?') !== false) {
    $requestUri = strstr($requestUri, '?', true);
}

// Routing:
if ($requestMethod === 'GET' && $requestUri === '/') {
    // Formular zeigen
    include __DIR__ . '/templates/form.html';
    exit;
}

// POST /shorten -> URL verkürzen
if ($requestMethod === 'POST' && $requestUri === '/shorten') {
    shortenUrl();
    exit;
}

// GET /abc123 -> Weiterleitung
if ($requestMethod === 'GET' && $requestUri !== '/') {
    $shortCode = ltrim($requestUri, '/');  // "/abc123" -> "abc123"
    redirectUrl($shortCode);
    exit;
}

// Fallback: 404
http_response_code(404);
echo "Seite nicht gefunden!";
