<!doctype html>
<html><head><meta charset="utf-8"><title>Client B</title></head>
<body>
  <h1>Client B</h1>
  <p>Achète Produit Y (10$)</p>

  <form method="POST" action="/create-checkout-session.php">
    <input type="hidden" name="client_key" value="B">
    <input type="hidden" name="product_key" value="Y">
    <button type="submit">Payer</button>
  </form>
</body></html>
