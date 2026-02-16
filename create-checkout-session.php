<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

\Stripe\Stripe::setApiKey(envOrFail('STRIPE_SECRET_KEY'));
$baseUrl = envOrFail('BASE_URL');

try {
  $session = \Stripe\Checkout\Session::create([
    'mode' => 'payment',
    'line_items' => [[
      'price_data' => [
        'currency' => 'cad',
        'product_data' => [
          'name' => 'Produit test PoC',
        ],
        'unit_amount' => 1000,
      ],
      'quantity' => 1,
    ]],
    'success_url' => $baseUrl . '/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'  => $baseUrl . '/cancel.php',
    'metadata' => [
      'poc_order_ref' => 'ORDER-' . time(),
    ],
  ]);

  header("HTTP/1.1 303 See Other");
  header("Location: " . $session->url);
  exit;

} catch (Exception $e) {
  http_response_code(500);
  echo "Erreur Stripe: " . htmlspecialchars($e->getMessage());
}

