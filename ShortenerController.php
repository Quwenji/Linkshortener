<?php
// shortenerController.php

require_once __DIR__ . '/database.php';  // Wir brauchen getConnection()

/**
 * Erzeugt einen neuen Kurzlink (POST /shorten)
 * - Erwartet einen POST-Parameter "longUrl"
 */
function shortenUrl()
{
    // 1. URL aus POST holen
    $longUrl = $_POST['longUrl'] ?? '';

    // 2. Validieren
    if (empty($longUrl) || !filter_var($longUrl, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        echo "Fehler: Bitte eine gültige URL angeben!";
        return;
    }

    // 3. Code generieren
    $shortCode = generateCode(6);

    // 4. In DB speichern
    $pdo = getConnection();
    $stmt = $pdo->prepare("INSERT INTO links (code, long_url) VALUES (:code, :longUrl)");
    $stmt->execute([
        ':code' => $shortCode,
        ':longUrl' => $longUrl
    ]);

    // 5. Kurz-URL ausgeben
    $shortUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $shortCode;
    echo "<p>Kurzlink erstellt: <a href='$shortUrl' target='_blank'>$shortUrl</a></p>";
    echo "<p><a href='/'>Zurück zum Formular</a></p>";
}

/**
 * Weiterleitung (GET /abc123)
 */
function redirectUrl($shortCode)
{
    if (empty($shortCode)) {
        http_response_code(400);
        echo "Kein Shortcode angegeben!";
        return;
    }

    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT long_url FROM links WHERE code = :code LIMIT 1");
    $stmt->execute([':code' => $shortCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo "Kurzlink nicht gefunden!";
        return;
    }

    // Weiterleitung
    header('Location: ' . $row['long_url'], true, 302);
    exit;
}

/**
 * Erzeugt einen zufälligen String, z.B. 6 Zeichen aus [0-9a-zA-Z].
 */
function generateCode($length = 6)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}
