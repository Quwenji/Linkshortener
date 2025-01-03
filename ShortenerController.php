<?php
// shortenerController.php

require_once __DIR__ . '/database.php';

/**
 * Überprüft, ob ein String unerwünschte Wörter enthält.
 */
function isBlacklisted($string)
{
    // Blacklist aus externer Datei laden
    $blacklist = include __DIR__ . '/blacklist.php';

    foreach ($blacklist as $word) {
        if (stripos($string, $word) !== false) {
            return true; // Wort gefunden
        }
    }

    return false; // Kein Wort gefunden
}

/**
 * POST /shorten -> URL verkürzen (mit optionalem Custom-Alias).
 */
function shortenUrl()
{
    $longUrl = $_POST['longUrl'] ?? '';
    $userAlias = $_POST['alias'] ?? '';

    // 1. Validierung der longUrl
    if (empty($longUrl) || !filter_var($longUrl, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        echo "<div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
                <p class='font-semibold'>Fehler:</p>
                <p>Bitte eine gültige URL angeben!</p>
              </div>";
        return;
    }

    // 2. Alias verarbeiten
    if (empty($userAlias)) {
        // Zufälligen Code generieren
        do {
            $code = generateCode(6);
        } while (isBlacklisted($code));
    } else {
        // Alias säubern (nur alphanumerisch + -, _)
        $alias = preg_replace('/[^a-zA-Z0-9-_]/', '', $userAlias);
        if (empty($alias)) {
            http_response_code(400);
            echo "<div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
                    <p class='font-semibold'>Fehler:</p>
                    <p>Alias enthält ungültige Zeichen.</p>
                  </div>";
            return;
        }

        // Überprüfen, ob Alias auf der Blacklist steht
        if (isBlacklisted($alias)) {
            http_response_code(400);
            echo "<div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
                    <p class='font-semibold'>Fehler:</p>
                    <p>Alias enthält unerwünschte Wörter und ist nicht erlaubt.</p>
                  </div>";
            return;
        }

        $code = $alias;
    }

    // 3. Prüfen, ob Code/Alias schon existiert
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM links WHERE code = :code");
    $stmt->execute([':code' => $code]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        http_response_code(409); // Conflict
        echo "<div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
                <p class='font-semibold'>Fehler:</p>
                <p>Der Alias <b>$code</b> ist leider schon vergeben.</p>
              </div>";
        return;
    }

    // 4. In DB speichern
    $stmt = $pdo->prepare("INSERT INTO links (code, long_url) VALUES (:code, :long_url)");
    $stmt->execute([
        ':code' => $code,
        ':long_url' => $longUrl
    ]);

    // 5. Erfolgsmeldung
    $shortUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $code;

    echo "<div class='bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded mb-4'>
            <p class='font-semibold'>Erfolg:</p>
            <p>Dein Kurzlink:
                <a class='underline text-blue-600' href='$shortUrl' target='_blank'>$shortUrl</a>
            </p>
          </div>";
}

/**
 * GET /{code} -> Weiterleitung zur langen URL.
 */
function redirectUrl($code)
{
    if (empty($code)) {
        http_response_code(400);
        echo "<div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
                <p class='font-semibold'>Fehler:</p>
                <p>Kein Code angegeben!</p>
              </div>";
        return;
    }

    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT long_url FROM links WHERE code = :code LIMIT 1");
    $stmt->execute([':code' => $code]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo "<div class='bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4'>
                <p class='font-semibold'>Fehler:</p>
                <p>Alias oder Code <b>$code</b> nicht gefunden!</p>
              </div>";
        return;
    }

    // Weiterleitung zur langen URL
    header('Location: ' . $row['long_url'], true, 302);
    exit;
}

/**
 * Erzeugt einen zufälligen String (z.B. 6 Zeichen [0-9a-zA-Z]).
 */
function generateCode($length = 6)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $index = random_int(0, strlen($chars) - 1);
        $result .= $chars[$index];
    }
    return $result;
}
