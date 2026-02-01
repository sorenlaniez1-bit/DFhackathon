# 🤝 Fais ta BA ! - Plateforme d'Entraide Solidaire

Une plateforme web innovante qui permet aux utilisateurs de s'entraider au quotidien. Les demandeurs peuvent publier des missions d'aide, tandis que les bénévoles les réalisent en échange de points échangeables dans la boutique.

## 📋 Table des Matières

- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure de la Base de Données](#-structure-de-la-base-de-données)
- [Utilisation](#-utilisation)
- [Captures d'Écran](#-captures-décran)
- [Roadmap](#-roadmap)
- [Accessibilité](#-accessibilité)

## ✨ Fonctionnalités

### Pour les Demandeurs
- ✅ Création de demandes d'aide avec description détaillée
- ✅ Gestion des propositions de bénévoles
- ✅ Sélection du bénévole et validation de mission
- ✅ Système de commentaires sur les bénévoles après mission terminée
- ✅ Suivi de l'état des demandes (ouverte, en cours, terminée)
- ✅ Attribution automatique de points selon la durée (10 points/heure)

### Pour les Bénévoles
- ✅ Consultation des missions disponibles
- ✅ Proposition pour des missions
- ✅ Suivi des propositions en attente de validation
- ✅ Accès aux missions acceptées avec adresse complète
- ✅ Gain de points après validation de mission
- ✅ Boutique pour échanger les points (tickets cinéma, bons d'achat)
- ✅ Profil public avec biographie et commentaires reçus

### Fonctionnalités Générales
- ✅ Système d'authentification sécurisé (SHA1)
- ✅ Rôles multiples (benevole, demandeur, ou les deux)
- ✅ Profil utilisateur avec statistiques détaillées
- ✅ Système de points et transactions
- ✅ Protection de la vie privée (adresse visible uniquement après acceptation)
- ✅ Interface responsive (mobile, tablette, desktop)
- ✅ Design accessible (WCAG AAA) avec contraste élevé

## 🛠 Technologies

### Backend
- **PHP 7.4+** - Langage serveur (approche procédurale)
- **MySQL/MariaDB** - Base de données relationnelle
- **PDO** - Prepared statements pour la sécurité SQL

### Frontend
- **HTML5** - Structure sémantique
- **CSS3** - Styles avec thème bleu nuit
- **JavaScript** - Interactions côté client

### Environnement
- **WAMP64** - Serveur local Windows (Apache, MySQL, PHP)

## 🚀 Installation

### Prérequis
- WAMP64 (ou XAMPP/MAMP selon votre OS)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Navigateur web moderne

### Étapes d'Installation

1. **Cloner le repository**
```bash
git clone https://github.com/votre-username/hackaton.git
cd hackaton
```

2. **Configurer la base de données**
- Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
- Créer une base de données nommée `hackaton`
- Importer le fichier SQL de structure (voir section Base de Données)

3. **Configurer la connexion**
Éditer `config.php` avec vos paramètres :
```php
$host = '192.168.1.62'; // ou 'localhost'
$db   = 'hackaton';
$user = 'root';
$pass = 'root'; // ou '' selon votre config
```

4. **Exécuter les scripts SQL**
Dans l'ordre :
```sql
-- 1. Ajouter la colonne ville
SOURCE ajouter_colonne_ville.sql;

-- 2. Ajouter la colonne bio
SOURCE ajouter_colonne_bio.sql;

-- 3. Ajouter la colonne commentaire
SOURCE ajouter_commentaire_benevole.sql;

-- 4. Insérer les articles boutique
SOURCE insert_articles_boutique.sql;

-- 5. Mettre à jour les biographies
SOURCE update_biographies.sql;
```

5. **Lancer le serveur**
- Démarrer WAMP
- Accéder à http://localhost/hackaton

## ⚙️ Configuration

### Structure des Fichiers Principaux

```
hackaton/
├── config.php                  # Configuration DB
├── index.php                   # Page d'accueil
├── login.php / register.php    # Authentification
├── dashboard.php               # Tableau de bord
├── profil.php                  # Profil utilisateur
├── modifier_profil.php         # Édition profil
│
├── creer_demande.php           # Création mission (demandeur)
├── mes_demandes.php            # Liste demandes (demandeur)
├── voir_propositions.php       # Voir propositions (demandeur)
├── commenter_benevole.php      # Laisser commentaire
│
├── missions.php                # Missions disponibles (bénévole)
├── mes_propositions.php        # Propositions en attente (bénévole)
├── mes_missions.php            # Missions acceptées (bénévole)
├── boutique.php                # Boutique points
│
├── style.css                   # Styles CSS
├── img/                        # Images (logo)
└── *.sql                       # Scripts SQL
```

## 🗄️ Structure de la Base de Données

### Tables Principales

#### `utilisateurs`
- `id` (INT, PK)
- `username` (VARCHAR)
- `password` (VARCHAR, SHA1)
- `nom`, `prenom` (VARCHAR)
- `age` (INT)
- `ville` (VARCHAR)
- `roles` (SET: 'benevole', 'demandeur')
- `bio` (TEXT) - Biographie personnelle
- `date_creation` (DATETIME)

#### `demandes`
- `id` (INT, PK)
- `titre`, `description` (VARCHAR/TEXT)
- `ville` (VARCHAR) - Ville publique
- `lieu` (VARCHAR) - Adresse complète (privée)
- `duree` (DECIMAL) - Durée en heures
- `date_limite` (DATETIME)
- `points_attribues` (INT) - Calculé : durée × 10
- `statut` (ENUM: 'ouverte', 'prise_en_charge', 'terminee')
- `id_demandeur` (INT, FK → utilisateurs)
- `id_benevole` (INT, FK → utilisateurs, nullable)
- `commentaire_benevole` (TEXT) - Commentaire du demandeur

#### `mises_en_relation`
- `id` (INT, PK)
- `id_demande` (INT, FK → demandes)
- `id_benevole` (INT, FK → utilisateurs)
- `statut` (ENUM: 'propose', 'accepte', 'refuse')
- `date_proposition` (DATETIME)

#### `points`
- `id_utilisateur` (INT, PK/FK → utilisateurs)
- `points` (INT) - Solde actuel

#### `transactions_points`
- `id` (INT, PK)
- `id_utilisateur` (INT, FK → utilisateurs)
- `montant` (INT)
- `type` (ENUM: 'gain', 'depense')
- `description` (VARCHAR)
- `date_transaction` (DATETIME)

#### `boutique`
- `id` (INT, PK)
- `nom`, `description` (VARCHAR/TEXT)
- `prix_points` (INT) - 1€ = 10 points
- `stock` (INT)
- `actif` (BOOLEAN)

#### `achats`
- `id` (INT, PK)
- `id_utilisateur` (INT, FK → utilisateurs)
- `id_article` (INT, FK → boutique)
- `date_achat` (DATETIME)

## 📖 Utilisation

### Inscription et Connexion
1. Créer un compte sur `/register.php`
2. Choisir vos rôles (bénévole, demandeur, ou les deux)
3. Se connecter sur `/login.php`

### En tant que Demandeur
1. Créer une demande avec titre, description, lieu (ville + adresse), durée
2. Attendre les propositions de bénévoles
3. Consulter les profils des bénévoles (avec bio)
4. Accepter un bénévole pour votre mission
5. Marquer la mission terminée
6. Laisser un commentaire public sur le bénévole

### En tant que Bénévole
1. Parcourir les missions disponibles (seule la ville est visible)
2. Cliquer sur "J'y vais" pour se proposer
3. Suivre ses propositions en attente
4. Une fois accepté, voir l'adresse complète
5. Réaliser la mission
6. Recevoir les points après validation
7. Dépenser les points dans la boutique

## 🎨 Captures d'Écran

*(À ajouter après déploiement)*

## 🗺️ Roadmap

### Fonctionnalités Futures
- [ ] Système de messagerie entre demandeur/bénévole
- [ ] Notifications par email
- [ ] Système d'évaluation par étoiles
- [ ] Filtres de recherche (catégorie, ville, durée)
- [ ] Catégories de missions (jardinage, informatique, courses...)
- [ ] Interface admin pour modération
- [ ] Export de données utilisateur (RGPD)
- [ ] Intégration API de cartographie
- [ ] Application mobile (PWA)

### Améliorations Techniques
- [ ] Migration vers password_hash() (bcrypt)
- [ ] Système de cache (Redis)
- [ ] Tests unitaires (PHPUnit)
- [ ] Documentation API REST
- [ ] Migration vers framework MVC

## ♿ Accessibilité

Le site respecte les normes **WCAG AAA** :
- ✅ Contraste texte/fond : 21:1 (blanc #ffffff sur bleu nuit #0f1729)
- ✅ Police haute lisibilité : Arial, Verdana (18-48px)
- ✅ Bordures épaisses (3-4px) pour meilleure visibilité
- ✅ Navigation au clavier complète
- ✅ Labels explicites pour tous les champs
- ✅ Responsive design (480px, 768px, 1024px+)

## 📱 Responsive Design

Breakpoints :
- **Mobile** : < 480px
- **Tablette** : 480px - 768px
- **Desktop** : > 1024px

## 🔒 Sécurité

- **Prepared Statements** : Protection contre injections SQL
- **Sessions PHP** : Gestion sécurisée des connexions
- **Validation serveur** : Tous les inputs validés côté backend
- **Privacy by design** : Adresse masquée jusqu'à acceptation
- **SHA1** : Hash des mots de passe (à migrer vers bcrypt)

## 👥 Utilisateurs de Test

```
# Demandeur
Username: marie_d
Password: SHA1(Marie)

# Bénévole
Username: jean_b
Password: SHA1(Jean)

# Double rôle
Username: sophie_l
Password: SHA1(Sophie)
```

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet a été réalisé dans le cadre d'un hackathon.

## 👨‍💻 Auteur

Projet développé pour promouvoir l'entraide locale et le lien social.

---

**Note** : Ce projet utilise WAMP64. Pour d'autres environnements (XAMPP, MAMP), adapter les chemins et la configuration en conséquence.

## 🆘 Support

Pour toute question ou problème :
1. Vérifier que WAMP est démarré
2. Vérifier les paramètres dans `config.php`
3. Consulter les logs PHP dans `php_error.log`
4. Vérifier que toutes les tables SQL sont créées

**Bon développement et bonnes actions ! 🌟**
