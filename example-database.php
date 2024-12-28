<?php
// Database.php

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
            $host = 'localhost';
            $dbName = 'shortener';
            $username = 'root';
            $password = '';

            $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (PDOException $e) {
                die("Fehler bei der DB-Verbindung: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
