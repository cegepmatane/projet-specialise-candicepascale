<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CandysJewel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="main-header">
    <div class="header-container">

        <a href="/index.php" class="logo">
            CandysJewel
        </a>

        <nav class="main-nav">
            <a href="/index.php">Accueil</a>
            <a href="/nouveautes.php">Nouveautés</a>
            <a href="/homme.php">Homme</a>
            <a href="/femme.php">Femme</a>
            <a href="/enfant.php">Enfant</a>
            <a href="/entretien.php">Entretien</a>
            <a href="/contact.php">Contact</a>
        </nav>

    </div>
</header>
