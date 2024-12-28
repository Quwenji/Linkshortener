<?php
// ShortenerController.php

class ShortenerController
{
    public static function shorten()
    {
        $longUrl = $_POST['longUrl'] ?? '';
        if (empty($longUrl) || !filter_var($longUrl, FILTER_VALIDATE_URL)) {
            http_response_code(400);
            echo "Bitte eine gültige URL angeben.";
            return;
        }

        $shortCode = self::generateCode(6);

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO links (code, long_url) VALUES (:code, :long_url)");
        $stmt->execute([
            ':code' => $shortCode,
            ':long_url' => $longUrl,
        ]);

        $shortUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $shortCode;
        echo "<p>Kurzlink erstellt: <a href='$shortUrl' target='_blank'>$shortUrl</a></p>";
        echo "<p><a href='/'>Zurück</a></p>";
    }

    public static function redirect($shortCode)
    {
        if (empty($shortCode)) {
            http_response_code(400);
            echo "Kein Code angegeben.";
            return;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT long_url FROM links WHERE code = :code LIMIT 1");
        $stmt->execute([':code' => $shortCode]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            http_response_code(404);
            echo "Kurzlink nicht gefunden.";
            return;
        }

        header("Location: " . $row['long_url'], true, 302);
        exit;
    }

    private static function generateCode($length = 6)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }
}
