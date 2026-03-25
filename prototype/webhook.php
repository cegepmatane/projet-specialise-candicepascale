<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/accesseur/Configuration.php';
require_once __DIR__ . '/donnees/CommandeDAO.php';
require_once __DIR__ . '/donnees/EvenementUtilisateurDAO.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if ($payload === false || empty($sigHeader)) {
    error_log('Webhook Stripe invalide : payload ou signature manquant.');
    http_response_code(400);
    exit('Bad request');
}

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sigHeader,
        STRIPE_WEBHOOK_SECRET
    );
} catch (\UnexpectedValueException $e) {
    error_log('Webhook Stripe payload invalide : ' . $e->getMessage());
    http_response_code(400);
    exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    error_log('Webhook Stripe signature invalide : ' . $e->getMessage());
    http_response_code(400);
    exit('Invalid signature');
} catch (\Exception $e) {
    error_log('Erreur inattendue webhook Stripe : ' . $e->getMessage());
    http_response_code(400);
    exit('Webhook error');
}

/* MODE TEST : on accepte aussi les événements non-live */

/*
if (!isset($event->livemode) || $event->livemode !== true) {
    error_log('Webhook Stripe ignoré : événement non-live reçu.');
    http_response_code(200);
    exit('Ignored');
}
*/

if (($event->type ?? '') !== 'checkout.session.completed') {
    http_response_code(200);
    exit('Ignored');
}

$session = $event->data->object ?? null;

if (!$session) {
    error_log('Webhook Stripe : session absente dans l’événement.');
    http_response_code(200);
    exit('Ignored');
}

$commandeId = $session->metadata->commande_id ?? $session->client_reference_id ?? null;

if (!$commandeId) {
    error_log('Webhook Stripe : commande_id manquant.');
    http_response_code(200);
    exit('Ignored');
}

$commandeId = (int)$commandeId;

if ($commandeId <= 0) {
    error_log('Webhook Stripe : commande_id invalide.');
    http_response_code(200);
    exit('Ignored');
}

$commande = CommandeDAO::trouverParId($commandeId);

if (!$commande) {
    error_log("Webhook Stripe : commande introuvable pour commande_id={$commandeId}");
    http_response_code(200);
    exit('Ignored');
}

if ($commande->obtenir('statut') === 'payee') {
    http_response_code(200);
    exit('Already processed');
}

$paymentIntent = $session->payment_intent ?? null;
$sessionId = $session->id ?? null;

if (empty($sessionId)) {
    error_log("Webhook Stripe : session_id manquant pour commande_id={$commandeId}");
    http_response_code(200);
    exit('Ignored');
}

/*
|--------------------------------------------------------------------------
| ETAPE 1 : vérification montant/devise
|--------------------------------------------------------------------------
*/
$montantAttendu = (int) round(((float) $commande->obtenir('montant_total')) * 100);
$montantPaye = (int) ($session->amount_total ?? 0);
$currency = strtolower((string) ($session->currency ?? ''));

error_log("Mismatch DEBUG | commande={$commandeId} | attendu={$montantAttendu} | paye={$montantPaye} | devise={$currency}");

if ($montantPaye !== $montantAttendu || $currency !== 'cad') {
    error_log("Mismatch Stripe | commande={$commandeId} | attendu={$montantAttendu} | paye={$montantPaye} | devise={$currency}");

    CommandeDAO::mettreAJourStatut(
        $commandeId,
        'failed',
        $sessionId,
        $paymentIntent
    );

    http_response_code(200);
    exit('Mismatch');
}

/*
|--------------------------------------------------------------------------
| ETAPE 2 : remettre la décrémentation du stock
|--------------------------------------------------------------------------
*/
$stockMisAJour = CommandeDAO::decrementerStockCommande($commandeId);

if (!$stockMisAJour) {
    error_log("Stock error | commande={$commandeId}");

    CommandeDAO::mettreAJourStatut(
        $commandeId,
        'failed',
        $sessionId,
        $paymentIntent
    );

    http_response_code(200);
    exit('Stock error');
}

$statutMisAJour = CommandeDAO::mettreAJourStatut(
    $commandeId,
    'payee',
    $sessionId,
    $paymentIntent
);

if (!$statutMisAJour) {
    error_log("Webhook Stripe : échec mise à jour statut payee pour commande_id={$commandeId}");
    http_response_code(200);
    exit('Update error');
}

$utilisateurId = (int)$commande->obtenir('utilisateur_id');
$lignes = $commande->obtenirLignes();

if ($utilisateurId > 0 && !empty($lignes)) {
    foreach ($lignes as $ligne) {
        $bijouId = (int)($ligne['bijou_id'] ?? 0);

        if ($bijouId > 0) {
            EvenementUtilisateurDAO::ajouterEvenement(
                $utilisateurId,
                $bijouId,
                'achat'
            );
        }
    }
}

http_response_code(200);
echo 'ok';
