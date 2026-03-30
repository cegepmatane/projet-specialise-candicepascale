# Cas d’utilisation – Achat d’un bijou

## Acteur
Utilisateur

## Scénario principal
1. L’utilisateur accède au site
2. Il consulte le catalogue
3. Il sélectionne un bijou
4. Il consulte les détails
5. Il ajoute au panier
6. Il accède au panier
7. Il procède au paiement
8. Il effectue le paiement via Stripe
9. Le système confirme la commande

## Résultat attendu
- Le paiement est validé
- La commande est enregistrée
- L’utilisateur reçoit une confirmation

## Cas alternatifs
- Stock insuffisant
- Paiement refusé
- Produit indisponible

## Système
- Vérifie le stock
- Traite le paiement
- Met à jour la base de données
