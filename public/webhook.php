<?php
require_once __DIR__ . '/../private/config.php';
require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// 1) Lire le RAW body (obligatoire pour vérifier la signature)
$payload = file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload,
    $sig_header,
    STRIPE_WEBHOOK_SECRET
  );
} catch (\UnexpectedValueException $e) {
  http_response_code(400);
  exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
  http_response_code(400);
  exit('Invalid signature');
}

// 2) Traiter l’événement Checkout
if ($event->type === 'checkout.session.completed') {
  /** @var \Stripe\Checkout\Session $session */
  $session = $event->data->object;

  // order_id vient de metadata (Technique 1) ou client_reference_id
  $order_id = $session->metadata->order_id ?? $session->client_reference_id ?? null;
  if (!$order_id) {
    http_response_code(200);
    exit('No order_id');
  }

  $paid_amount = $session->amount_total;   // en cents
  $currency = $session->currency;          // ex: "cad"
  $payment_intent = $session->payment_intent ?? null;

  $pdo = db();

  // 3) Charger la commande en BD
  $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id=?");
  $stmt->execute([$order_id]);
  $order = $stmt->fetch();

  if (!$order) {
    http_response_code(200);
    exit('Order not found');
  }

  // 4) Vérifications BD vs Stripe (ce que veut ta prof)
  $expected_amount = (int)$order['expected_amount'];
  $expected_currency = strtolower($order['currency']);

  if ((int)$paid_amount !== $expected_amount || strtolower($currency) !== $expected_currency) {
    $pdo->prepare("
      UPDATE orders
      SET status='failed', paid_amount=?, stripe_payment_intent_id=?, paid_at=NOW()
      WHERE order_id=?
    ")->execute([$paid_amount, $payment_intent, $order_id]);

    http_response_code(200);
    exit('Mismatch marked failed');
  }

  // 5) OK → paid
  $pdo->prepare("
    UPDATE orders
    SET status='paid', paid_amount=?, stripe_payment_intent_id=?, paid_at=NOW()
    WHERE order_id=?
  ")->execute([$paid_amount, $payment_intent, $order_id]);
}

http_response_code(200);
echo "ok";
