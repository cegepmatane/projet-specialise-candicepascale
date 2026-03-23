<?php

require_once __DIR__ . "/EvenementUtilisateurDAO.php";
require_once __DIR__ . "/BijouDAO.php";

class RecommandationDAO
{
    public static function obtenirRecommandationsPourUtilisateur(
        int $utilisateurId,
        int $limite = 4
    ): array {
        $evenements = EvenementUtilisateurDAO::listerEvenementsParUtilisateur($utilisateurId);

        if (empty($evenements)) {
            return [];
        }

        $poidsEvenements = [
            'vue' => 1,
            'ajout_panier' => 3,
            'achat' => 5
        ];

        $profil = [
            'categories' => [],
            'materiaux' => [],
            'pierres' => [],
            'prix_total' => 0,
            'prix_count' => 0
        ];

        $bijouxInteragis = [];

        foreach ($evenements as $evenement) {
            $type = (string)($evenement['type_evenement'] ?? '');
            $poids = $poidsEvenements[$type] ?? 0;

            $categorieId = (int)($evenement['categorie_id'] ?? 0);
            $materiau = trim((string)($evenement['materiau'] ?? ''));
            $pierre = trim((string)($evenement['pierre'] ?? ''));
            $prix = (float)($evenement['prix'] ?? 0);
            $bijouId = (int)($evenement['bijou_id'] ?? 0);

            if ($bijouId > 0) {
                $bijouxInteragis[$bijouId] = true;
            }

            if ($categorieId > 0) {
                if (!isset($profil['categories'][$categorieId])) {
                    $profil['categories'][$categorieId] = 0;
                }
                $profil['categories'][$categorieId] += $poids;
            }

            if ($materiau !== '') {
                if (!isset($profil['materiaux'][$materiau])) {
                    $profil['materiaux'][$materiau] = 0;
                }
                $profil['materiaux'][$materiau] += $poids;
            }

            if ($pierre !== '') {
                if (!isset($profil['pierres'][$pierre])) {
                    $profil['pierres'][$pierre] = 0;
                }
                $profil['pierres'][$pierre] += $poids;
            }

            if ($prix > 0) {
                $profil['prix_total'] += $prix;
                $profil['prix_count']++;
            }
        }

        $prixMoyen = 0;
        if ($profil['prix_count'] > 0) {
            $prixMoyen = $profil['prix_total'] / $profil['prix_count'];
        }

        $bijoux = BijouDAO::listerBijoux();
        $resultats = [];

        foreach ($bijoux as $bijou) {
            $bijouId = (int)$bijou->obtenir('id');

            if ($bijouId <= 0) {
                continue;
            }

            // On évite de recommander un bijou déjà consulté/ajouté/acheté
            if (isset($bijouxInteragis[$bijouId])) {
                continue;
            }

            // On évite les bijoux sans stock
            $variantes = $bijou->obtenir('variantes') ?? [];
            $aDuStock = false;

            foreach ($variantes as $variante) {
                if ((int)($variante['stock'] ?? 0) > 0) {
                    $aDuStock = true;
                    break;
                }
            }

            if (!$aDuStock) {
                continue;
            }

            $score = 0;

            $categorieId = (int)$bijou->obtenir('categorie_id');
            $materiau = trim((string)$bijou->obtenir('materiau'));
            $pierre = trim((string)$bijou->obtenir('pierre'));
            $prix = (float)$bijou->obtenir('prix');

            if (isset($profil['categories'][$categorieId])) {
                $score += $profil['categories'][$categorieId];
            }

            if ($materiau !== '' && isset($profil['materiaux'][$materiau])) {
                $score += $profil['materiaux'][$materiau];
            }

            if ($pierre !== '' && isset($profil['pierres'][$pierre])) {
                $score += $profil['pierres'][$pierre];
            }

            if ($prixMoyen > 0 && $prix > 0) {
                $ecart = abs($prix - $prixMoyen);

                if ($ecart <= 10) {
                    $score += 4;
                } elseif ($ecart <= 25) {
                    $score += 2;
                } elseif ($ecart <= 50) {
                    $score += 1;
                }
            }

            if ($score > 0) {
                $resultats[] = [
                    'bijou' => $bijou,
                    'score' => $score
                ];
            }
        }

        usort($resultats, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_slice($resultats, 0, $limite);
    }
}
