<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/accesseur/Configuration.php';
require_once __DIR__ . '/donnees/CommandeDAO.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

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

/*
|--------------------------------------------------------------------------
| Sécurité : ignorer les événements non-live si on est en production
|--------------------------------------------------------------------------
*/
if (!isset($event->livemode) || $event->livemode !== true) {
    http_response_code(200);
    exit('Ignoring non-live event');
}

if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;

    $commandeId = $session->metadata->commande_id ?? $session->client_reference_id ?? null;

    if (!$commandeId) {
        http_response_code(200);
        exit('No commande_id');
    }

    $commande = CommandeDAO::trouverParId((int)$commandeId);

    if (!$commande) {
        http_response_code(200);
        exit('Commande introuvable');
    }

    /*
    |--------------------------------------------------------------------------
    | Éviter les doubles traitements
    |--------------------------------------------------------------------------
    */
    if ($commande->obtenir('statut') === 'payee') {
        http_response_code(200);
        exit('Already paid');
    }

    $montantAttendu = (int) round(((float)$commande->obtenir('montant_total')) * 100);
    $montantPaye = (int) $session->amount_total;
    $currency = strtolower($session->currency ?? '');
    $paymentIntent = $session->payment_intent ?? null;

    /*
    |--------------------------------------------------------------------------
    | Vérification montant + devise
    |--------------------------------------------------------------------------
    */
    if ($montantPaye !== $montantAttendu || $currency !== 'cad') {
        CommandeDAO::mettreAJourStatut(
            (int)$commandeId,
            'failed',
            $session->id,
            $paymentIntent
        );

        http_response_code(200);
        exit('Mismatch marked failed');
    }

    /*
    |--------------------------------------------------------------------------
    | Paiement confirmé
    |--------------------------------------------------------------------------
    */
    CommandeDAO::mettreAJourStatut(
        (int)$commandeId,
        'payee',
        $session->id,
        $paymentIntent
    );
}

http_response_code(200);
echo "ok";
