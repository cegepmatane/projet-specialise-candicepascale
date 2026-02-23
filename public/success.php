<?php
require_once __DIR__ . '/../private/db.php';

$order_id = $_GET['order_id'] ?? '';
if (!$order_id) exit("order_id manquant");

$stmt = db()->prepare("SELECT * FROM orders WHERE order_id=?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

echo "<h1>Success</h1>";
echo "<pre>" . htmlspecialchars(print_r($order, true)) . "</pre>";
