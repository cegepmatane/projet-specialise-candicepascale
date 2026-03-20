<?php

require_once __DIR__ . "/../accesseur/Configuration.php";

class AccesseurEvenementUtilisateur
{
    public static $basededonnees = null;

    protected static function initialiser(): void
    {
        if (self::$basededonnees === null) {
            self::$basededonnees = Connexion::getInstance();
        }
    }
}

class EvenementUtilisateurDAO extends AccesseurEvenementUtilisateur
{
    public const SQL_AJOUTER_EVENEMENT = "
        INSERT INTO evenements_utilisateur (
            utilisateur_id,
            bijou_id,
            type_evenement,
            date_evenement
        ) VALUES (
            :utilisateur_id,
            :bijou_id,
            :type_evenement,
            NOW()
        )
    ";

    public const SQL_LISTER_EVENEMENTS_UTILISATEUR = "
        SELECT
            e.id,
            e.utilisateur_id,
            e.bijou_id,
            e.type_evenement,
            e.date_evenement,
            b.categorie_id,
            b.materiau,
            b.pierre,
            b.prix,
            b.nom
        FROM evenements_utilisateur e
        INNER JOIN bijoux b ON b.id = e.bijou_id
        WHERE e.utilisateur_id = :utilisateur_id
          AND b.actif = 1
        ORDER BY e.date_evenement DESC
    ";

    public static function ajouterEvenement(
        int $utilisateurId,
        int $bijouId,
        string $typeEvenement
    ): bool {
        self::initialiser();

        $typesAutorises = ['vue', 'ajout_panier', 'achat'];

        if (!in_array($typeEvenement, $typesAutorises, true)) {
            return false;
        }

        $statement = self::$basededonnees->prepare(self::SQL_AJOUTER_EVENEMENT);
        $statement->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $statement->bindValue(':bijou_id', $bijouId, PDO::PARAM_INT);
        $statement->bindValue(':type_evenement', $typeEvenement, PDO::PARAM_STR);

        return $statement->execute();
    }

    public static function listerEvenementsParUtilisateur(int $utilisateurId): array
    {
        self::initialiser();

        $statement = self::$basededonnees->prepare(self::SQL_LISTER_EVENEMENTS_UTILISATEUR);
        $statement->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
