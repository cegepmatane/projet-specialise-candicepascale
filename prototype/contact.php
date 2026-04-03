<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config_mail.php';
require_once __DIR__ . '/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erreurs = [];
$succes = false;

$nom = '';
$email = '';
$sujet = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $website = trim($_POST['website'] ?? ''); // honeypot

    if (empty($csrf) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
        $erreurs[] = "Requête invalide.";
    }

    if ($website !== '') {
        $erreurs[] = "Envoi refusé.";
    }

    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom === '' || mb_strlen($nom) < 2) {
        $erreurs[] = "Le nom est obligatoire.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L’adresse email est invalide.";
    }

    if ($sujet === '' || mb_strlen($sujet) < 3) {
        $erreurs[] = "Le sujet est obligatoire.";
    }

    if ($message === '' || mb_strlen($message) < 10) {
        $erreurs[] = "Le message est trop court.";
    }

    if (empty($erreurs)) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = MAIL_PORT;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
            $mail->addAddress(MAIL_TO_EMAIL, 'Administration Jewelry by PC');
            $mail->addReplyTo($email, $nom);

            $mail->isHTML(true);
            $mail->Subject = 'Contact site - ' . $sujet;

            $nomSafe = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
            $emailSafe = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
            $sujetSafe = htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8');
            $messageSafe = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

            $mail->Body = "
                <h2>Nouveau message depuis la page contact</h2>
                <p><strong>Nom :</strong> {$nomSafe}</p>
                <p><strong>Email :</strong> {$emailSafe}</p>
                <p><strong>Sujet :</strong> {$sujetSafe}</p>
                <hr>
                <p>{$messageSafe}</p>
            ";

            $mail->AltBody =
                "Nouveau message depuis la page contact\n\n" .
                "Nom : {$nom}\n" .
                "Email : {$email}\n" .
                "Sujet : {$sujet}\n\n" .
                "Message :\n{$message}";

            $mail->send();

            $succes = true;
            $nom = '';
            $email = '';
            $sujet = '';
            $message = '';

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            $erreurs[] = "Le message n’a pas pu être envoyé. Vérifie la configuration SMTP.";
        }
    }
}
?>

<main class="page-contact-luxe">
    <section class="hero-contact-luxe">
        <div class="hero-contact-luxe__overlay"></div>
        <div class="hero-contact-luxe__contenu">
            <p class="sur-titre-contact">Jewelry by PC</p>
            <h1>Contactez-nous</h1>
            <p>
                Une question, une demande spéciale ou un besoin d’assistance ?
                Notre équipe est à votre écoute.
            </p>
        </div>
    </section>

    <section class="section-contact-luxe">
        <div class="conteneur-contact-luxe">
            <div class="bloc-formulaire-luxe">
                <h2>Envoyer un message</h2>
                <p class="intro-formulaire-luxe">
                    Remplissez ce formulaire et nous vous répondrons dès que possible.
                </p>

                <?php if ($succes): ?>
                    <div class="alerte-contact succes-contact">
                        Votre message a bien été envoyé.
                    </div>
                <?php endif; ?>

                <?php if (!empty($erreurs)): ?>
                    <div class="alerte-contact erreur-contact">
                        <ul>
                            <?php foreach ($erreurs as $erreur): ?>
                                <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="contact.php" class="formulaire-contact-luxe">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <div class="champ-invisible">
                        <label for="website">Site web</label>
                        <input type="text" name="website" id="website" autocomplete="off">
                    </div>

                    <div class="grille-formulaire-contact">
                        <div class="champ-contact">
                            <label for="nom">Nom complet</label>
                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="champ-contact">
                            <label for="email">Adresse email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="champ-contact">
                        <label for="sujet">Sujet</label>
                        <input
                            type="text"
                            id="sujet"
                            name="sujet"
                            value="<?= htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >
                    </div>

                    <div class="champ-contact">
                        <label for="message">Message</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="7"
                            required
                        ><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <button type="submit" class="btn-contact-luxe">
                        Envoyer le message
                    </button>
                </form>
            </div>

            <aside class="bloc-infos-luxe">
                <div class="carte-info-luxe">
                    <h3>Nos coordonnées</h3>
                    <p><strong>Email :</strong> contact@candysjewel.com</p>
                    <p><strong>Téléphone :</strong> +1 581 000 0000</p>
                    <p><strong>Adresse :</strong> Matane, Québec, Canada</p>
                </div>

                <div class="carte-info-luxe">
                    <h3>Horaires</h3>
                    <p>Lundi à vendredi : 9 h à 17 h</p>
                    <p>Samedi : 10 h à 15 h</p>
                    <p>Dimanche : Fermé</p>
                </div>

                <div class="carte-info-luxe">
                    <h3>Réseaux sociaux</h3>
                    <div class="liens-sociaux-luxe">
                        <a href="#" class="social-item">
                            <span class="icon-social">
                                <i class="fab fa-instagram"></i>
                            </span>
                            <span>Instagram</span>
                        </a>

                        <a href="#" class="social-item">
                            <span class="icon-social">
                                <i class="fab fa-facebook-f"></i>
                            </span>
                            <span>Facebook</span>
                        </a>

                        <a href="#" class="social-item">
                            <span class="icon-social">
                                <i class="fab fa-pinterest-p"></i>
                            </span>
                            <span>Pinterest</span>
                        </a>
                    </div>
                </div>

                <div class="carte-info-luxe carte-securite-luxe">
                    <h3>Confiance & sécurité</h3>
                    <p>
                        Vos échanges sont traités avec soin, et vos paiements sur la boutique
                        sont sécurisés via Stripe.
                    </p>
                </div>
            </aside>
        </div>
    </section>

    <section class="section-map-luxe">
        <div class="conteneur-map-luxe">
            <h2>Nous trouver</h2>
            <div class="map-luxe">
                <iframe
                    src="https://www.google.com/maps?q=Matane,Quebec&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
