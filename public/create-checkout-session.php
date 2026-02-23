<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../private/config.php';
require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$client_key  = $_POST['client_key']  ?? '';
$product_key = $_POST['product_key'] ?? '';

$catalog = [
  'A:X' => ['amount' => 500,  'currency' => 'cad', 'label' => 'Produit X (5$)'],
  'B:Y' => ['amount' => 1000, 'currency' => 'cad', 'label' => 'Produit Y (10$)'],
];

$key = $client_key . ':' . $product_key;
if (!isset($catalog[$key])) {
  http_response_code(400);
  exit('Combinaison client/produit invalide.');
}

$item = $catalog[$key];
$order_id = bin2hex(random_bytes(16));

$pdo = db();

// 1) commande pending
$pdo->prepare("
  INSERT INTO orders(order_id, client_key, product_key, expected_amount, currency, status, created_at)
  VALUES(?, ?, ?, ?, ?, 'pending', NOW())
")->execute([$order_id, $client_key, $product_key, $item['amount'], $item['currency']]);

// 2) session Stripe + metadata
$session = \Stripe\Checkout\Session::create([
  'mode' => 'payment',
  'client_reference_id' => $order_id,
  'metadata' => [
    'order_id' => $order_id,
    'client_key' => $client_key,
    'product_key' => $product_key,
    'expected_amount' => (string)$item['amount'],
    'currency' => $item['currency'],
  ],
  'line_items' => [[
    'price_data' => [
      'currency' => $item['currency'],
      'unit_amount' => $item['amount'],
      'product_data' => ['name' => $item['label']],
    ],
    'quantity' => 1,
  ]],
  'success_url' => BASE_URL . '/success.php?order_id=' . $order_id,
  'cancel_url'  => BASE_URL . '/cancel.php?order_id=' . $order_id,
]);

// 3) sauver stripe_session_id
$pdo->prepare("UPDATE orders SET stripe_session_id=? WHERE order_id=?")
    ->execute([$session->id, $order_id]);

header("Location: " . $session->url, true, 303);
exit;
