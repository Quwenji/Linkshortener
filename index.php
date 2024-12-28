<?php
// index.php

// Manuell deine Dateien einbinden, statt composer autoload
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/ShortenerController.php';

// Einfaches Routing
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if (strpos($requestUri, '?') !== false) {
    $requestUri = strstr($requestUri, '?', true);
}

// GET / => Formular
if ($requestMethod === 'GET' && $requestUri === '/') {
    include __DIR__ . '/templates/form.html';
    exit;
}

// POST /shorten => Link verkürzen
if ($requestMethod === 'POST' && $requestUri === '/shorten') {
    ShortenerController::shorten();
    exit;
}

// GET /irgendwas => Redirect
if ($requestMethod === 'GET' && $requestUri !== '/') {
    $shortCode = ltrim($requestUri, '/');
    ShortenerController::redirect($shortCode);
    exit;
}

http_response_code(404);
echo "Seite nicht gefunden.";
