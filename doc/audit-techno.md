# Audit technologique – CandysJewel

---

## 1. Mise en contexte

### Réponse IA
Le projet CandysJewel est une application e-commerce développée en PHP avec une base de données MySQL. Il permet la gestion d’un catalogue de bijoux, un système de panier et un paiement sécurisé via Stripe. Les dépendances critiques sont Stripe (paiement), PHP (backend) et MySQL (base de données).

### Annotation
✅ D’accord
Cela correspond bien à mon projet. J’ai effectivement utilisé PHP et MySQL pour gérer les données et Stripe pour les paiements. Stripe est particulièrement critique car si quelque chose ne fonctionne pas, le paiement ne peut pas être effectué.

---

## 2. A) Santé des dépendances

### Réponse IA
Les dépendances principales sont maintenues et largement utilisées. Aucune dépendance obsolète n’est détectée.

### Annotation
⚠️ Partiellement d’accord
Dans mon projet, j’utilise des technologies récentes comme PHP et Stripe, donc elles sont bien maintenues. Par contre, je n’ai pas vérifié les versions exactes ni les vulnérabilités. Donc l’IA a raison en général, mais dans mon cas il pourrait quand même y avoir des risques que je n’ai pas vérifiés.

---

## 3. B) Maturité des technologies

### Réponse IA
PHP, MySQL et Stripe sont des technologies matures, stables et largement adoptées dans l’industrie.

### Annotation
✅ D’accord
Pendant mon projet, je n’ai pas eu de problème majeur avec ces technologies. Elles sont bien documentées et faciles à utiliser, ce qui m’a aidé à avancer rapidement.

---

## 4. C) Architecture et choix techniques

### Réponse IA
Le projet présente une architecture simple mais pourrait être amélioré avec une meilleure séparation des responsabilités (pattern MVC). Il existe un mélange entre logique métier et affichage.

### Annotation
⚠️ Partiellement d’accord
Dans mon projet, j’ai essayé d’organiser le code avec des fichiers DAO pour accéder à la base de données. Mais c’est vrai que dans certaines pages, j’ai encore du PHP directement dans le HTML. Donc l’IA a raison, mon projet pourrait être mieux structuré.

---

## 5. D) Alternatives émergentes

### Réponse IA
Des alternatives comme Laravel, Node.js ou Firebase pourraient améliorer la structure et la scalabilité du projet.

### Annotation
❌ Pas d’accord
Pour mon projet, je pense que ces solutions ne sont pas nécessaires. J’ai réalisé ce projet dans un contexte scolaire avec un temps limité. Utiliser Laravel ou Node.js aurait rendu le projet plus complexe à réaliser. PHP simple était suffisant pour atteindre les objectifs.

---

## 6. Plan d’action

### Réponse IA

HAUTE priorité :
- Sécuriser les entrées utilisateur
- Vérifier les paiements côté serveur
- Prévenir les injections SQL

MOYENNE priorité :
- Améliorer la structure du code
- Ajouter une gestion des erreurs

BASSE priorité :
- Ajouter logs et monitoring
- Surveiller les dépendances

### Annotation
✅ D’accord
Dans mon projet, je n’ai pas implémenté toutes les mesures de sécurité avancées. Donc ces recommandations sont pertinentes, surtout pour les validations et la sécurité des paiements.

---

## 7. Résumé exécutif

### Réponse IA
Le projet CandysJewel est basé sur des technologies solides mais présente des améliorations possibles en sécurité et en architecture.

### Annotation
⚠️ Partiellement d’accord
Je suis d’accord avec le résumé, mais je pense que la sécurité est encore plus importante que ce que l’IA suggère, surtout pour un site avec paiement.

---

## 8. Note globale

### Réponse IA
8/10

### Annotation
⚠️ Plutot d’accord
Je trouve que la note est correcte, meme si mon projet manque encore des améliorations en organisation du code.
