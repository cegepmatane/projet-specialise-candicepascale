<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

\Stripe\Stripe::setApiKey(envOrFail('STRIPE_SECRET_KEY'));
$webhookSecret = envOrFail('STRIPE_WEBHOOK_SECRET');

$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

$logFile = __DIR__ . '/storage/events.log';
$ordersFile = __DIR__ . '/storage/orders.json';

function logLine(string $msg, string $file): void {
  $ts = date('Y-m-d H:i:s');
  file_put_contents($file, "[$ts] $msg\n", FILE_APPEND);
}

try {
  $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
} catch (\UnexpectedValueException $e) {
  http_response_code(400);
  logLine("Invalid payload", $logFile);
  exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
  http_response_code(400);
  logLine("Invalid signature", $logFile);
  exit('Invalid signature');
}

logLine("Event: {$event->type} id={$event->id}", $logFile);

if ($event->type === 'checkout.session.completed') {
  $session = $event->data->object;

  $orderRef = $session->metadata->poc_order_ref ?? 'UNKNOWN';
  $amountTotal = $session->amount_total ?? null;
  $currency = $session->currency ?? null;
  $paymentStatus = $session->payment_status ?? null;

  $orders = json_decode(@file_get_contents($ordersFile), true);
  if (!is_array($orders)) $orders = [];

  $orders[] = [
    'order_ref' => $orderRef,
    'session_id' => $session->id ?? null,
    'payment_status' => $paymentStatus,
    'amount_total' => $amountTotal,
    'currency' => $currency,
    'created_at' => date('c'),
  ];

  file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));
  logLine("Saved order: $orderRef status=$paymentStatus amount=$amountTotal $currency", $logFile);
}

http_response_code(200);
echo "OK";

