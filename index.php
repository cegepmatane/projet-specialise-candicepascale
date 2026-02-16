<?php
require __DIR__ . '/config.php';
$baseUrl = envOrFail('BASE_URL');
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>PoC Stripe Checkout</title>

  <!-- Lien vers le CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="container">
    <h1>PoC Stripe Checkout</h1>
    <p>Objectif : créer une session Checkout et recevoir un webhook.</p>

    <form method="POST" action="create-checkout-session.php">
      <button type="submit">Payer 10,00 $ CAD (test)</button>
    </form>

    <p class="notice">
      Carte test Stripe uniquement : 4242 4242 4242 4242
    </p>

    <div class="footer">
      Webhook : <code><?= htmlspecialchars($baseUrl) ?>/webhook.php</code>
    </div>
  </div>

</body>
</html>
