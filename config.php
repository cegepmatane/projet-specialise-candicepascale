<?php

// ===============================
// 1️⃣ Chargement du fichier .env
// ===============================

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        http_response_code(500);
        exit("Fichier .env introuvable: $path\n");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // Ignorer commentaires
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        // Séparer clé=valeur
        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

        $key = trim($key);
        $value = trim($value);

        // Injection robuste dans l'environnement système
        putenv("$key=$value");
    }
}

// Charger le .env
loadEnv(__DIR__ . '/.env');


// ===============================
// 2️⃣ Lecture sécurisée des variables
// ===============================

function envOrFail(string $key): string
{
    $value = getenv($key);

    if ($value === false || $value === '') {
        http_response_code(500);
        exit("Config manquante: $key\n");
    }

    return $value;
}


// ===============================
// 3️⃣ Préparer le dossier storage
// ===============================

function ensureStorage(): void
{
    $dir = __DIR__ . '/storage';

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $logFile = $dir . '/events.log';
    $ordersFile = $dir . '/orders.json';

    if (!file_exists($logFile)) {
        file_put_contents($logFile, "");
    }

    if (!file_exists($ordersFile)) {
        file_put_contents($ordersFile, json_encode([], JSON_PRETTY_PRINT));
    }
}

// Initialiser le stockage
ensureStorage();


// ===============================
// 4️⃣ Debug optionnel (désactivé)
// ===============================

// Décommente si besoin
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

