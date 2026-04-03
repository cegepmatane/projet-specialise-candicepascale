<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit;
}

require_once "header.php";
?>

<main class="page-compte-simple">
    <h2>Mon compte</h2>

    <p>
        Bienvenue
        <strong>
            <?= htmlspecialchars($_SESSION['utilisateur']['prenom'], ENT_QUOTES, 'UTF-8') ?>
            <?= htmlspecialchars($_SESSION['utilisateur']['nom'], ENT_QUOTES, 'UTF-8') ?>
        </strong>
    </p>

    <p>Email : <?= htmlspecialchars($_SESSION['utilisateur']['email'], ENT_QUOTES, 'UTF-8') ?></p>

    <hr>

    <ul>
        <li><a href="panier.php">Voir mon panier</a></li>
        <li><a href="mes-commandes.php">Mes commandes</a></li>
        <li><a href="favoris.php">Mes favoris</a></li>
        <li><a href="deconnexion.php">Se déconnecter</a></li>
    </ul>
</main>

<?php require_once "footer.php"; ?>
