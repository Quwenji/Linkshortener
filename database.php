<?php
// database.php

/**
 * Liest Variablen aus einer .env-Datei und gibt sie als assoziatives Array zurück.
 */
function parseEnv($envPath)
{
    if (!file_exists($envPath)) {
        // Notfallbehandlung, wenn .env fehlt
        die(".env-Datei nicht gefunden! Pfad: $envPath");
    }

    $vars = [];
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Zeilen, die mit # beginnen, ignorieren (Kommentare)
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // In Form: SCHLUESSEL=WERT
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
 * Stellt eine PDO-Verbindung her.
 */
function getConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        // (1) .env-Datei parsen
        $envPath = __DIR__ . '/.env'; // Falls .env im gleichen Ordner wie database.php liegt
        $env = parseEnv($envPath);

        // (2) Datenbankinfos holen
        $host = $env['DB_HOST'] ?? 'localhost';
        $dbName = $env['DB_NAME'] ?? '';
        $user = $env['DB_USER'] ?? 'root';
        $pass = $env['DB_PASS'] ?? '';

        // (3) DSN bauen
        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            // Falls was schief geht
            die("DB-Verbindungsfehler: " . $e->getMessage());
        }
    }

    return $pdo;
}
