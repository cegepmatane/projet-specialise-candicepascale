<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/donnees/FavoriDAO.php";

if (!isset($_SESSION['utilisateur']['id'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrfTokenFormulaire = $_POST['csrf_token'] ?? '';
$csrfTokenSession = $_SESSION['csrf_token'] ?? '';

if (
    empty($csrfTokenFormulaire) ||
    empty($csrfTokenSession) ||
    !hash_equals($csrfTokenSession, $csrfTokenFormulaire)
) {
    $_SESSION['message_favori'] = "Requête invalide.";
    header("Location: index.php");
    exit;
}

$bijouId = filter_input(INPUT_POST, 'bijou_id', FILTER_VALIDATE_INT);
$bijouId = $bijouId ?: 0;

if ($bijouId <= 0) {
    $_SESSION['message_favori'] = "Bijou invalide.";
    header("Location: index.php");
    exit;
}

FavoriDAO::ajouterFavori((int)$_SESSION['utilisateur']['id'], $bijouId);

$_SESSION['message_favori'] = "Bijou ajouté aux favoris.";
header("Location: detail-bijou.php?id=" . $bijouId);
exit;
