<!doctype html>
<html><head><meta charset="utf-8"><title>Client A</title></head>
<body>
  <h1>Client A</h1>
  <p>Achète Produit X (5$)</p>

  <form method="POST" action="/create-checkout-session.php">
    <input type="hidden" name="client_key" value="A">
    <input type="hidden" name="product_key" value="X">
    <button type="submit">Payer</button>
  </form>
</body></html>
