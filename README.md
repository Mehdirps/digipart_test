# Digipart Test - Système de Gestion de Produits

Ce projet est un système simple de gestion de produits développé en PHP, permettant d'effectuer des opérations CRUD (Create, Read, Update, Delete) sur des produits avec gestion d'images.

## Fonctionnalités

- Ajouter des produits avec images
- Afficher la liste des produits
- Modifier les informations des produits
- Supprimer des produits
- Gestion intelligente des images (conservation ou remplacement)

## Structure de la base de données

Le système utilise deux tables principales:

1. **products**: Stocke les informations de base du produit
   - id (clé primaire)
   - reference
   - title
   - image

2. **product_infos**: Stocke les informations détaillées du produit
   - id (clé primaire)
   - product_id (clé étrangère vers products.id)
   - description
   - price

## Structure du projet

```
digipart_test/
├── class/
│   └── product.class.php    # Classe Product avec méthodes CRUD
├── includes/
│   └── header.php           # En-tête commun du site
├── uploads/                 # Dossier pour stocker les images uploadées
├── index.php                # Page d'accueil (ajout de produit)
├── products_list.php        # Liste des produits
├── update_product.php       # Modification d'un produit
├── db.php                   # Configuration de la base de données
└── README.md                # Ce fichier
```

## Explications du code

La classe `Product` contient des commentaires détaillés expliquant le fonctionnement des méthodes principales :

### Méthode create()

Cette méthode utilise des transactions pour garantir l'intégrité des données lors de l'insertion dans les deux tables. Elle nettoie les données entrantes pour prévenir les injections SQL.

### Méthode update()

La méthode de mise à jour gère intelligemment les images:
- Si une nouvelle image est téléchargée, l'ancienne est supprimée du serveur
- Si aucune image n'est fournie, l'image existante est conservée

### Méthode delete()

La méthode de suppression:
- Supprime l'image du produit du serveur
- Supprime les enregistrements dans les tables dans le bon ordre pour respecter les contraintes de clés étrangères
- Utilise des transactions pour garantir l'intégrité des données

## Gestion des images

Le système gère les images de manière efficace:
1. Seules les extensions jpg, jpeg et png sont acceptées
2. La taille est limitée à 1MB
3. Les noms de fichiers sont générés de façon unique avec la fonction uniqid()
4. Les anciennes images sont automatiquement supprimées lors de la mise à jour

## Sécurité

- Protection contre les injections SQL via PDO et la fonction htmlspecialchars()
- Validation des types de fichiers téléchargés
- Gestion des erreurs avec try/catch et transactions
