<?php include __DIR__ . '/includes/header.php'; ?>

<main>

  <!-- HERO : grand visuel style luxe -->
Sélection du moment
  <section class="hero hero-swar">
    <div class="hero-media hero-media-img"
Sélection du moment
         style="background-image:url('https://source.unsplash.com/1920x1080/?jewelry,crystal,luxury');"></div>

    <div class="hero-overlay">
      <div class="hero-content">
        <p class="hero-kicker">Nouvelle collection</p>
        <h1>Éclat minimal, élégance maximale.</h1>
        <p class="hero-sub">Des bijoux sobres, pensés pour durer. Homme • Femme • Enfant.</p>
        <div class="hero-ctas">
          <a class="btn" href="/nouveautes.php">Découvrir les nouveautés</a>
          <a class="btn ghost" href="/femme.php">Voir Femme</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Bandeau promo / éditorial -->
  <section class="container reveal">
    <div class="promo-strip">
      <div>
        <strong>Livraison</strong><br>
        <span>Rapide & soignée (démo)</span>
      </div>
      <div>
        <strong>Qualité</strong><br>
        <span>Finitions nettes</span>
      </div>
      <div>
        <strong>Entretien</strong><br>
        <span>Conseils selon matériaux</span>
      </div>
    </div>
  </section>

  <!-- Catégories principales : gros blocs -->
  <section class="container reveal">
    <div class="section-title">
      <h2>Magasiner par univers</h2>
      <p>Une navigation simple, claire.</p>
    </div>

    <div class="cat-grid">
      <a class="cat-card" href="/nouveautes.php">
        <img src="https://source.unsplash.com/900x900/?jewelry,diamond" alt="Nouveautés">
        <span>Nouveautés</span>
      </a>

      <a class="cat-card" href="/femme.php">
        <img src="https://source.unsplash.com/900x900/?necklace,jewelry,woman" alt="Femme">
        <span>Femme</span>
      </a>

      <a class="cat-card" href="/homme.php">
        <img src="https://source.unsplash.com/900x900/?watch,jewelry,men" alt="Homme">
        <span>Homme</span>
      </a>

      <a class="cat-card" href="/enfant.php">
        <img src="https://source.unsplash.com/900x900/?bracelet,jewelry,kids" alt="Enfant">
        <span>Enfant</span>
      </a>
    </div>
  </section>

  <!-- Section “Best sellers / Sélection” style vitrine -->
  <section class="container reveal">
    <div class="section-title">
      <h2>Sélection du moment</h2>
      <p>Des pièces qui vont avec tout.</p>
    </div>

    <div class="products products-home">
      <?php
      // Mode statique: cartes “produit” sans DB (juste pour l’affichage)
      $fake = [
        ["Bague Minimal", "79.99 $", "https://source.unsplash.com/900x1100/?ring,jewelry"],
        ["Montre Classique", "149.99 $", "https://source.unsplash.com/900x1100/?watch,luxury"],
        ["Collier Perle", "99.99 $", "https://source.unsplash.com/900x1100/?necklace,pearl"],
      ];
      foreach($fake as $p){
        echo '
        <div class="product-card">
          <img src="'.$p[2].'" alt="">
          <div class="pmeta">
            <div class="pname">'.$p[0].'</div>
            <div class="pprice">'.$p[1].'</div>
          </div>
          <div class="pactions">
            <a class="btn small ghost" href="/catalog.php">Voir</a>
            <a class="btn small" href="/catalog.php">Ajouter</a>
          </div>
        </div>';
      }
      ?>
    </div>
  </section>

  <!-- Bloc éditorial double : très “luxe” -->
  <section class="container reveal">
    <div class="editorial">
      <div class="editorial-text">
        <h2>Entretien & durabilité</h2>
        <p>
          Prolonge la brillance : nettoyage doux, rangement séparé, bonnes pratiques selon métal/pierre.
        </p>
        <a class="btn ghost" href="/entretien.php">Voir les conseils</a>
      </div>

      <div class="editorial-media">
        <img src="https://source.unsplash.com/1200x900/?jewelry,cleaning,cloth" alt="Entretien bijoux">
      </div>
    </div>
  </section>

  <!-- “Inspiration” : grille d’images type lookbook -->
  <section class="container reveal">
    <div class="section-title">
      <h2>Inspiration</h2>
      <p>Des styles sobres, faciles à porter.</p>
    </div>

    <div class="lookbook">
      <img src="https://source.unsplash.com/900x900/?earrings,jewelry" alt="">
      <img src="https://source.unsplash.com/900x900/?bracelet,luxury" alt="">
      <img src="https://source.unsplash.com/900x900/?ring,diamond" alt="">
      <img src="https://source.unsplash.com/900x900/?necklace,gold" alt="">
    </div>
  </section>

</main>



<?php include __DIR__ . '/includes/footer.php'; ?>
