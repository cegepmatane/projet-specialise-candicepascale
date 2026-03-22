<?php
include 'header.php';
require_once __DIR__ . '/donnees/RecommandationDAO.php';

$recommandations = [];

if (isset($_SESSION['utilisateur']['id'])) {
    $recommandations = RecommandationDAO::obtenirRecommandationsPourUtilisateur(
        (int)$_SESSION['utilisateur']['id'],
        4
    );
}
?>

<main class="page-accueil">

    <section class="hero-accueil hero-slider">
        <div class="hero-slides">
            <div class="hero-slide actif" style="background-image: url('images/img1.jpg');"></div>
            <div class="hero-slide" style="background-image: url('images/img2.jpg');"></div>
            <div class="hero-slide" style="background-image: url('images/img3.jpg');"></div>
            <div class="hero-slide" style="background-image: url('images/img4.jpg');"></div>
        </div>

        <div class="hero-overlay"></div>

        <div class="hero-contenu">
            <p class="sur-titre">Bijoux élégants • Femme • Homme • Enfant</p>
            <h2>Bienvenue chez Jewelry by PC</h2>
            <p>
                Découvrez une collection de bijoux élégants, raffinés et tendances
                pour femme, homme et enfant. Offrez-vous l’éclat que vous méritez.
            </p>
            <div class="hero-boutons">
                <a href="categorie.php" class="picture">Magasiner maintenant</a>
                <a href="nouveautes.php" class="picture">Découvrir les nouveautés</a>
            </div>
        </div>

        <div class="hero-indicateurs">
            <button type="button" class="hero-dot actif" data-slide="0" aria-label="Slide 1"></button>
            <button type="button" class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
            <button type="button" class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
            <button type="button" class="hero-dot" data-slide="3" aria-label="Slide 4"></button>
        </div>
    </section>

    <?php if (isset($_SESSION['utilisateur']['id']) && !empty($recommandations)): ?>
        <section class="recommandations-accueil">
            <h2>Recommandé pour vous</h2>

            <div class="grille-bijoux">
                <?php foreach ($recommandations as $item): ?>
                    <?php
                    $bijou = $item['bijou'];
                    $images = $bijou->obtenir('images') ?? [];
                    $imagePrincipale = !empty($images) ? ($images[0]['chemin_image'] ?? '') : '';
                    ?>

                    <article class="carte-bijou">
                        <div class="media-carte-bijou">
                            <?php if (!empty($imagePrincipale)): ?>
                                <img
                                    src="<?= htmlspecialchars($imagePrincipale, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars((string)$bijou->obtenir('nom'), ENT_QUOTES, 'UTF-8') ?>"
                                    class="image-carte-bijou"
                                >
                            <?php else: ?>
                                <div class="image-vide">Aucune image</div>
                            <?php endif; ?>
                        </div>

                        <div class="contenu-carte-bijou">
                            <span class="tag-reco">Suggestion personnalisée</span>

                            <h3><?= htmlspecialchars((string)$bijou->obtenir('nom'), ENT_QUOTES, 'UTF-8') ?></h3>

                            <p class="prix-bijou">
                                <?= number_format((float)$bijou->obtenir('prix'), 2, ',', ' ') ?> $
                            </p>

                            <a href="detail-bijou.php?id=<?= (int)$bijou->obtenir('id') ?>" class="btn-detail">
                                Voir le détail
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="vedettes-accueil">
        <div class="entete-section">
            <h2>Nos bijoux en vedette</h2>
            <p>
                Découvrez une sélection élégante de pièces qui font rayonner notre collection.
            </p>
        </div>

        <div class="grille-vedettes">
            <article class="vedette-bijou vedette-large">
                <img src="images/img1.jpg" alt="Bijou vedette 1">
                <div class="overlay-vedette">
                    <span class="tag-vedette">Coup de cœur</span>
                    <h3>Éclat intemporel</h3>
                    <p>
                        Une pièce raffinée pensée pour sublimer chaque moment et révéler votre style.
                    </p>
                    <a href="categorie.php" class="picture">Découvrir</a>
                </div>
            </article>

            <article class="vedette-bijou">
                <img src="images/bagues/bague10.jpg" alt="Collection bagues">
                <div class="overlay-vedette">
                    <h3>Collection bagues</h3>
                    <a href="boutique.php?id=1" class="picture">Voir les bagues</a>
                </div>
            </article>

            <article class="vedette-bijou">
                <img src="images/img3.jpg" alt="Collection colliers">
                <div class="overlay-vedette">
                    <h3>Collection colliers</h3>
                    <a href="boutique.php?id=4" class="picture">Voir les colliers</a>
                </div>
            </article>
        </div>
    </section>

    <section class="categories-accueil">
        <h2>Nos catégories</h2>

        <div class="evenement">
            <div class="objet">
                <h3>Bagues</h3>
                <img src="images/bagues/bague3.jpg" alt="Collection colliers">
                <p>Des bagues élégantes pour sublimer chaque main.</p>
                <a href="boutique.php?id=1" class="picture">Voir les bagues</a>
            </div>

            <div class="objet">
                <h3>Bracelets</h3>
                <img src="images/bracelet.jpg" alt="Collection colliers">
                <p>Des bracelets délicats et tendance pour toutes les occasions.</p>
                <a href="boutique.php?id=2" class="picture">Voir les bracelets</a>
            </div>

            <div class="objet">
                <h3>Boucles d’oreilles</h3>
                <img src="images/bouicle3.jpg" alt="Collection colliers">
                <p>Des créations lumineuses pour compléter votre style.</p>
                <a href="boutique.php?id=3" class="picture">Voir les boucles d’oreilles</a>
            </div>

            <div class="objet">
                <h3>Colliers</h3>
                   <img src="images/colliers/collier10.jpg" alt="Collection colliers">
                <p>Des colliers raffinés pour ajouter une touche d’éclat.</p>
                <a href="boutique.php?id=4" class="picture">Voir les colliers</a>
            </div>
        </div>
    </section>

    <section class="avantages-boutique">
        <h2>Pourquoi choisir Jewelry by PC ?</h2>

        <div class="evenement">
            <div class="objet">
                <h3>Bijoux pour tous</h3>
                <p>Des collections pour femme, homme et enfant.</p>
            </div>

            <div class="objet">
                <h3>Style et élégance</h3>
                <p>Des bijoux choisis pour leur finesse et leur beauté.</p>
            </div>

            <div class="objet">
                <h3>Achat simplifié</h3>
                <p>Un parcours clair pour découvrir, choisir et commander.</p>
            </div>
        </div>
    </section>

    <section class="infolettre-bas">
        <div class="bloc-infolettre-bas">
            <h2>Restez connectés</h2>
            <p>Recevez nos nouveautés, promotions et inspirations bijoux.</p>

            <form action="index.php" method="post" class="form-infolettre-bas">
                <input type="email" name="email" placeholder="Adresse mail" required>
                <input type="submit" value="S’inscrire">
            </form>
        </div>
    </section>

</main>

<?php include 'footer.php'; ?>
