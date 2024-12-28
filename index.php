<?php
// index.php
require_once __DIR__ . '/shortenerController.php';

// Header einbinden
require_once __DIR__ . '/header.php';

// Routing
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Query-String entfernen
if (strpos($requestUri, '?') !== false) {
    $requestUri = strstr($requestUri, '?', true);
}

// GET / -> Formular anzeigen
if ($requestMethod === 'GET' && $requestUri === '/') {
    require_once __DIR__ . '/templates/form.php';
    require_once __DIR__ . '/footer.php';
    exit;
}

// POST /shorten -> URL verkürzen
if ($requestMethod === 'POST' && $requestUri === '/shorten') {
    shortenUrl();
    require_once __DIR__ . '/footer.php';
    exit;
}

// GET /irgendwas -> Weiterleitung
if ($requestMethod === 'GET' && $requestUri !== '/') {
    $code = ltrim($requestUri, '/');
    redirectUrl($code);
    require_once __DIR__ . '/footer.php';
    exit;
}

// Fallback: 404
http_response_code(404);
echo "<div class='p-4 border-l-4 border-red-500 bg-red-50 text-red-700 rounded mb-4'>
        Seite nicht gefunden.
      </div>";
require_once __DIR__ . '/footer.php';
exit;
