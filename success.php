<?php
require __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Paiement réussi</title>

  <!-- CSS commun -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="container">
    <div class="success-icon">✅</div>

    <h1>Paiement réussi</h1>

    <p class="success-message">
      Le paiement de test a été effectué avec succès.
    </p>

    <p>
      Cette page confirme que le flux Stripe Checkout fonctionne correctement
      dans le cadre de la preuve de concept.
    </p>

    <a href="index.php" class="back-link">Retour à la PoC</a>
  </div>

</body>
</html>
