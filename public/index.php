<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>PoC Stripe — Choix client</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Arial;max-width:720px;margin:40px auto;padding:0 16px}
    .card{border:1px solid #ddd;border-radius:12px;padding:18px;margin:14px 0}
    .row{display:flex;gap:12px;flex-wrap:wrap;align-items:center;justify-content:space-between}
    .btn{display:inline-block;padding:10px 14px;border-radius:10px;border:1px solid #111;text-decoration:none}
    .btn-primary{background:#111;color:#fff}
    .muted{color:#666}
  </style>
</head>
<body>
  <h1>PoC Stripe — Choisir un client</h1>
  <p class="muted">Clique sur un bouton pour ouvrir la page correspondante, puis payer via Stripe Checkout.</p>

  <div class="card">
    <div class="row">
      <div>
        <h2>Client A</h2>
        <div class="muted">Produit X — 5$</div>
      </div>
      <a class="btn btn-primary" href="/clientA.php">Payer (Client A)</a>
    </div>
  </div>

  <div class="card">
    <div class="row">
      <div>
        <h2>Client B</h2>
        <div class="muted">Produit Y — 10$</div>
      </div>
      <a class="btn btn-primary" href="/clientB.php">Payer (Client B)</a>
    </div>
  </div>

  <hr>
  <p class="muted">
    Debug : <a href="/clientA.php">clientA.php</a> • <a href="/clientB.php">clientB.php</a>
  </p>
</body>
</html>

