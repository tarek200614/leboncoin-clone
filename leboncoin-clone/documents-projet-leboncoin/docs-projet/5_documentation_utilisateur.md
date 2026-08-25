# Documentation Utilisateur — MonBonCoin
**Guide complet d'utilisation du site**
Université | Licence 1 Informatique | Année 2025-2026
**Étudiant :** MEGHARI ABDERRAHMANE TAREK & YESSINE MILED
---

## Introduction

Bienvenue sur **MonBonCoin**, votre plateforme de petites annonces en ligne. Ce guide vous explique comment utiliser toutes les fonctionnalités du site, étape par étape.

---

## 1. Accéder au site

Ouvrez votre navigateur web et tapez l'adresse :
```
http://localhost/LeBonCoin/index.php
```

Vous arrivez sur la **page d'accueil** qui affiche toutes les annonces disponibles.

---

## 2. Créer un compte

Pour publier des annonces, mettre des favoris ou envoyer des messages, vous devez d'abord créer un compte.

**Étapes :**
1. Cliquez sur **"Inscription"** dans le menu en haut à droite
2. Remplissez le formulaire :
   - **Pseudo** : votre nom affiché sur le site
   - **Email** : votre adresse email (elle sera votre identifiant)
   - **Mot de passe** : minimum 10 caractères
   - **Confirmation** : retapez le même mot de passe
3. Cliquez sur **"S'inscrire"**
4. Vous êtes automatiquement redirigé vers la page de connexion

> **Attention :** L'email doit être unique. Si vous voyez "Cet email est déjà utilisé", choisissez un autre email.

---

## 3. Se connecter

1. Cliquez sur **"Connexion"** dans le menu
2. Entrez votre **email** et votre **mot de passe**
3. Cliquez sur **"Se connecter"**
4. Vous êtes redirigé vers la page d'accueil, votre pseudo apparaît dans le menu

> En cas d'erreur : vérifiez que le verrouillage majuscules n'est pas activé.

---

## 4. Consulter les annonces

### Voir la liste des annonces
La page d'accueil affiche toutes les annonces sous forme de **cartes**. Chaque carte montre :
- La photo de l'article
- Le titre
- Le prix
- Le vendeur et la date

### Voir le détail d'une annonce
Cliquez sur une carte ou sur le titre d'une annonce pour voir :
- La description complète
- Toutes les photos
- Les informations du vendeur
- Le formulaire de contact

### Filtrer les annonces
Utilisez le formulaire de filtres en haut de la page d'accueil pour :
- **Rechercher** un mot dans le titre ou la description
- **Filtrer par catégorie** (Véhicules, Informatique, etc.)
- **Définir un prix minimum** et/ou **maximum**

Cliquez sur **"Filtrer"** pour appliquer. Cliquez sur **"Réinitialiser"** pour tout effacer.

---

## 5. Publier une annonce

Vous devez être **connecté** pour publier.

1. Cliquez sur **"+ Déposer une annonce"** dans le menu
2. Remplissez les champs obligatoires :
   - **Titre** : description courte de votre article
   - **Prix** : en euros (ex: 150.00)
   - **Description** : détaillez votre article (état, caractéristiques...)
   - **Photo** : sélectionnez une image (JPG, PNG, max 5 Mo)
3. Choisissez une **catégorie** (optionnel mais recommandé)
4. Cliquez sur **"Publier l'annonce"**

Votre annonce apparaît immédiatement sur le site.

---

## 6. Modifier une annonce

Seul le propriétaire de l'annonce peut la modifier.

1. Allez sur la page de votre annonce
2. Cliquez sur **"✏️ Modifier"**
3. Modifiez les champs souhaités (le formulaire est pré-rempli avec les valeurs actuelles)
4. Pour changer la photo : sélectionnez une nouvelle image (laissez vide pour conserver l'actuelle)
5. Cliquez sur **"Enregistrer les modifications"**

---

## 7. Supprimer une annonce

1. Allez sur la page de votre annonce
2. Cliquez sur **"🗑️ Supprimer"**
3. Une page de **confirmation** s'affiche — lisez-la attentivement
4. Cliquez sur **"Oui, supprimer définitivement"** pour confirmer
   ou sur **"Non, annuler"** pour revenir en arrière

> **Attention :** La suppression est définitive. Tous les messages liés à cette annonce seront aussi supprimés.

---

## 8. Les favoris

Vous devez être **connecté** pour utiliser les favoris.

### Ajouter aux favoris
Sur n'importe quelle annonce (liste ou page détail), cliquez sur **"☆ Favori"**.
L'étoile devient pleine **"★ Favori"** pour indiquer que c'est dans vos favoris.

### Voir vos favoris
Cliquez sur **"Mes favoris"** dans le menu.

### Retirer des favoris
Cliquez à nouveau sur **"★ Favori"** sur l'annonce, ou depuis la page "Mes favoris".

---

## 9. La messagerie

Vous devez être **connecté** pour envoyer des messages.

### Contacter un vendeur
1. Allez sur la page de l'annonce qui vous intéresse
2. Cliquez sur **"💬 Contacter le vendeur"**
3. Écrivez votre message dans la zone de texte
4. Cliquez sur **"Envoyer"**

### Consulter vos messages
1. Cliquez sur **"Messages"** dans le menu
2. La liste de vos conversations apparaît à gauche
3. Cliquez sur une conversation pour voir l'historique complet
4. Répondez directement depuis la zone de saisie en bas

### Répondre à un message
Depuis la page **"Mes messages"**, sélectionnez la conversation et écrivez dans la zone en bas. Vous pouvez aussi utiliser le raccourci **Ctrl+Entrée** pour envoyer rapidement.

---

## 10. Mon compte

Cliquez sur votre pseudo dans le menu pour accéder à votre tableau de bord. Vous y trouvez :
- Le nombre d'annonces publiées
- Le nombre de favoris
- Le nombre de messages non lus
- La liste de toutes vos annonces avec des boutons de modification/suppression

---

## 11. Se déconnecter

Cliquez sur **"Déconnexion"** dans le menu. Vous serez déconnecté et redirigé vers la page d'accueil.

---

## 12. Résolution des problèmes courants

| Problème | Solution |
|----------|----------|
| Je ne peux pas me connecter | Vérifiez votre email et mot de passe. Respectez les majuscules. |
| Ma photo ne s'upload pas | Vérifiez que le fichier fait moins de 5 Mo et est en JPG, PNG ou WEBP |
| Je ne vois pas le bouton "Modifier" | Vous n'êtes pas le propriétaire de cette annonce |
| La page est blanche | Vérifiez que MAMP est démarré (Apache et MySQL verts) |
| Je ne reçois pas de messages | Vérifiez la page "Mes messages" et rafraîchissez |

---


