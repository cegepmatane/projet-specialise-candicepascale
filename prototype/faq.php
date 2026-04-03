<?php require_once __DIR__ . "/header.php"; ?>

<main class="page-faq">
    <section class="hero-faq">
        <div class="hero-faq-contenu">
            <p class="sur-titre-faq">Jewelry by PC</p>
            <h1>Foire aux questions</h1>
            <p>
                Retrouvez ici les réponses aux questions les plus fréquentes
                concernant nos bijoux, les commandes, le paiement et l’utilisation du site.
            </p>
        </div>
    </section>

    <section class="section-faq">
        <div class="conteneur-faq">
            <div class="intro-faq">
                <h2>Questions fréquentes</h2>
                <p>
                    Cette page a été conçue pour vous aider à trouver rapidement
                    les informations importantes avant, pendant et après votre achat.
                </p>
            </div>

            <div class="liste-faq">
                <details class="item-faq" open>
                    <summary>Quels types de bijoux proposez-vous ?</summary>
                    <p>
                        Nous proposons différentes catégories de bijoux, notamment
                        des bagues, bracelets, boucles d’oreilles et colliers.
                        Chaque catégorie présente une sélection de produits avec
                        leur image, leur prix et leurs détails.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Comment consulter les détails d’un bijou ?</summary>
                    <p>
                        Depuis le catalogue ou une catégorie, il suffit de cliquer
                        sur le bouton « Voir le détail ». Vous accédez alors à une
                        fiche produit avec la description, le prix, le matériau,
                        les tailles disponibles et l’état du stock.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Comment savoir si un bijou est disponible ?</summary>
                    <p>
                        La disponibilité est affichée directement sur la fiche du bijou.
                        Si le stock est faible, un message d’alerte peut indiquer
                        qu’il ne reste que quelques unités. Si le produit n’est plus
                        disponible, un message « Stock épuisé » est affiché.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Puis-je choisir une taille avant l’ajout au panier ?</summary>
                    <p>
                        Oui. Pour les bijoux concernés, vous pouvez sélectionner une
                        taille avant d’ajouter le produit au panier. Le système vérifie
                        ensuite la disponibilité en fonction de la variante choisie.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Comment fonctionne le panier ?</summary>
                    <p>
                        Le panier vous permet d’ajouter plusieurs bijoux, de modifier
                        la quantité et de supprimer des articles avant de finaliser
                        votre commande. Le total est recalculé automatiquement.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Le paiement sur le site est-il sécurisé ?</summary>
                    <p>
                        Oui. Le paiement est sécurisé via Stripe. La validation de la
                        transaction est gérée côté serveur afin d’assurer un traitement
                        fiable et cohérent de la commande.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Dois-je être connecté pour accéder à certaines fonctionnalités ?</summary>
                    <p>
                        Oui. Certaines fonctionnalités comme les favoris, les commandes
                        et certaines actions personnalisées nécessitent une connexion
                        à votre compte utilisateur.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>À quoi servent les favoris ?</summary>
                    <p>
                        Les favoris vous permettent d’enregistrer les bijoux qui vous
                        intéressent afin de les retrouver plus facilement plus tard
                        depuis votre espace personnel.
                    </p>
                </details>

                <details class="item-faq">
                    <summary>Comment vous contacter si j’ai une autre question ?</summary>
                    <p>
                        Vous pouvez utiliser la page Contact pour nous envoyer un message.
                        Nous mettons également à disposition nos coordonnées, nos horaires
                        et nos réseaux sociaux.
                    </p>
                </details>
            </div>

            <div class="faq-action">
                <a href="contact.php" class="btn-faq-principal">Nous contacter</a>
                <a href="categorie.php" class="btn-faq-secondaire">Découvrir les bijoux</a>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . "/footer.php"; ?>
