<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/donnees/CommandeDAO.php';

echo "Fichier chargé<br>";

$panier = $_SESSION['panier'] ?? [];
$utilisateurConnecte = isset($_SESSION['utilisateur']) && !empty($_SESSION['utilisateur']['id']);

echo "Session OK<br>";

if (empty($panier)) {
    die("Panier vide");
}

if (!$utilisateurConnecte) {
    die("Utilisateur non connecté");
}

$utilisateurId = (int) $_SESSION['utilisateur']['id'];

echo "Avant creation commande<br>";

$commandeId = CommandeDAO::creerCommande($utilisateurId, $panier);

var_dump($commandeId);

if (!$commandeId) {
    die("Erreur lors de la création de la commande.");
}

$_SESSION['commande_en_cours'] = [
    'commande_id' => $commandeId,
    'articles' => $panier,
    'date' => date('Y-m-d H:i:s')
];

header("Location: paiement.php");
exit;
