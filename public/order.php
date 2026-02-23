<?php
require_once __DIR__ . '/../private/db.php';

$order_id = $_GET['id'] ?? '';
if (!$order_id) exit("Paramètre ?id= manquant");

$stmt = db()->prepare("SELECT * FROM orders WHERE order_id=?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) exit("Commande introuvable");

echo "<h1>Order</h1><pre>" . htmlspecialchars(print_r($order, true)) . "</pre>";
