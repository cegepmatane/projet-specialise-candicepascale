<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur']['id'])) {
    header("Location: connexion.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . "/donnees/FavoriDAO.php";
require_once __DIR__ . "/header.php";

$favoris = FavoriDAO::listerFavorisParUtilisateur((int) $_SESSION['utilisateur']['id']);
?>

<main class="page-favoris">
    <section class="conteneur-favoris">
        <h1>Mes favoris</h1>

        <?php if (isset($_SESSION['message_favori'])): ?>
            <p class="message-stock-ok">
                <?= htmlspecialchars($_SESSION['message_favori'], ENT_QUOTES, 'UTF-8') ?>
            </p>
            <?php unset($_SESSION['message_favori']); ?>
        <?php endif; ?>

        <?php if (empty($favoris)): ?>
            <div class="etat-vide-favoris">
                <p>Vous n’avez encore aucun bijou en favoris.</p>
                <a href="categorie.php" class="btn-detail">Découvrir les bijoux</a>
            </div>
        <?php else: ?>
            <div class="grille-bijoux">
                <?php foreach ($favoris as $bijou): ?>
                    <?php
                    $images = $bijou->obtenir('images') ?? [];
                    $imagePrincipale = !empty($images) ? ($images[0]['chemin_image'] ?? '') : '';
                    ?>
                    <article class="carte-bijou">
                        <div class="media-carte-bijou">
                            <?php if (!empty($imagePrincipale)): ?>
                                <img
                                    src="<?= htmlspecialchars($imagePrincipale, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars((string) $bijou->obtenir('nom'), ENT_QUOTES, 'UTF-8') ?>"
                                    class="image-carte-bijou"
                                >
                            <?php else: ?>
                                <div class="image-vide">Aucune image</div>
                            <?php endif; ?>
                        </div>

                        <div class="contenu-carte-bijou">
                            <h2><?= htmlspecialchars((string) $bijou->obtenir('nom'), ENT_QUOTES, 'UTF-8') ?></h2>

                            <p class="prix-bijou">
                                <?= number_format((float) $bijou->obtenir('prix'), 2, ',', ' ') ?> $
                            </p>

                            <div class="actions-carte-bijou">
                                <a
                                    href="detail-bijou.php?id=<?= (int) $bijou->obtenir('id') ?>"
                                    class="btn-detail"
                                >
                                    Voir le détail
                                </a>

                                <form action="supprimer_favori.php" method="post">
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="bijou_id"
                                        value="<?= (int) $bijou->obtenir('id') ?>"
                                    >
                                    <button type="submit" class="btn-supprimer">Retirer</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . "/footer.php"; ?>
