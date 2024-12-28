<?php
// database.php

/**
 * Liest Variablen aus einer .env-Datei (im Projekt-Root) und gibt sie als Array zurück.
 */
function parseEnv($envPath)
{
    if (!file_exists($envPath)) {
        die("Fehler: .env-Datei nicht gefunden ($envPath)");
    }

    $vars = [];
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Kommentarzeilen überspringen
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // KEY=VALUE
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            $vars[$key] = $value;
        }
    }

    return $vars;
}

/**
 * Stellt eine (Singleton-)PDO-Verbindung her, basierend auf den in .env definierten Daten.
 */
function getConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        // (1) .env-Datei parsen
        $envPath = __DIR__ . '/.env'; 
        $env = parseEnv($envPath);

        // (2) DB-Zugangsdaten
        $host = $env['DB_HOST'] ?? 'localhost';
        $dbName = $env['DB_NAME'] ?? '';
        $user = $env['DB_USER'] ?? 'root';
        $pass = $env['DB_PASS'] ?? '';

        // (3) DSN
        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Datenbank-Verbindungsfehler: " . $e->getMessage());
        }
    }

    return $pdo;
}
