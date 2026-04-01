<?php

require_once __DIR__ . "/../accesseur/Configuration.php";
require_once __DIR__ . "/BijouDAO.php";

class FavoriDAO
{
    public static ?PDO $basededonnees = null;

    protected static function initialiser(): void
    {
        if (self::$basededonnees === null) {
            self::$basededonnees = Connexion::getInstance();
        }
    }

    public static function listerFavorisParUtilisateur(int $utilisateurId): array
    {
        self::initialiser();

        $sql = "
            SELECT bijou_id
            FROM favoris
            WHERE utilisateur_id = :utilisateur_id
            ORDER BY date_ajout DESC
        ";

        $statement = self::$basededonnees->prepare($sql);
        $statement->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $statement->execute();

        $favoris = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $bijou = BijouDAO::trouverParId((int)$row['bijou_id']);

            if ($bijou !== null) {
                $favoris[] = $bijou;
            }
        }

        return $favoris;
    }

    public static function supprimerFavori(int $utilisateurId, int $bijouId): bool
    {
        self::initialiser();

        $sql = "
            DELETE FROM favoris
            WHERE utilisateur_id = :utilisateur_id
              AND bijou_id = :bijou_id
        ";

        $statement = self::$basededonnees->prepare($sql);
        $statement->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $statement->bindValue(':bijou_id', $bijouId, PDO::PARAM_INT);

        return $statement->execute();
    }

    public static function estEnFavori(int $utilisateurId, int $bijouId): bool
    {
        self::initialiser();

        $sql = "
            SELECT id
            FROM favoris
            WHERE utilisateur_id = :utilisateur_id
              AND bijou_id = :bijou_id
            LIMIT 1
        ";

        $statement = self::$basededonnees->prepare($sql);
        $statement->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $statement->bindValue(':bijou_id', $bijouId, PDO::PARAM_INT);
        $statement->execute();

        return (bool)$statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function ajouterFavori(int $utilisateurId, int $bijouId): bool
    {
        self::initialiser();

        if (self::estEnFavori($utilisateurId, $bijouId)) {
            return true;
        }

        $sql = "
            INSERT INTO favoris (utilisateur_id, bijou_id, date_ajout)
            VALUES (:utilisateur_id, :bijou_id, NOW())
        ";

        $statement = self::$basededonnees->prepare($sql);
        $statement->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        $statement->bindValue(':bijou_id', $bijouId, PDO::PARAM_INT);

        return $statement->execute();
    }
}
