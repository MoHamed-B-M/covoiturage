#  Rydo (Covoiturage)

**Rydo** est une application web de covoiturage haute performance, conçue pour faciliter la mise en relation entre conducteurs et passagers dans la région de Sidi Bouzid. Développée dans un cadre académique à l'ISET de Sidi Bouzid, cette plateforme allie une interface utilisateur futuriste et immersive à une architecture robuste et sécurisée.

## 🚀 Fonctionnalités principales

* **Gestion des trajets :** Les conducteurs peuvent publier leurs trajets (départ, destination, date, prix, places disponibles).
* **Recherche dynamique :** Moteur de recherche avancé permettant de filtrer les trajets par date, prix et disponibilité.
* **Système de réservation :** Gestion automatique des places disponibles lors des réservations.
* **Authentification sécurisée :** Gestion des sessions, hachage des mots de passe (bcrypt) et récupération de compte par e-mail.
* **Tableau de bord administrateur :** Outils de gestion complète des utilisateurs, des trajets et statistiques globales du système.
* **Interface moderne :** Design "Futuristic/Dark mode" intégrant Tailwind CSS, des effets WebGL avec Three.js et des animations fluides.

## 🛠️ Stack Technique

* **Backend :** PHP (orienté objet et procédural)
* **Base de données :** MySQL (via PDO pour la sécurité)
* **Frontend :** Tailwind CSS, Vanilla JS, Three.js (WebGL)
* **Emails :** PHPMailer (SMTP)
* **Architecture :** Modèle MVC simplifié (LAMP)

## 📁 Structure du projet

* `db.php` : Configuration de la connexion à la base de données.
* `register.php` / `login.php` : Gestion des accès utilisateurs.
* `search.php` : Cœur du moteur de recherche de trajets.
* `add_trip.php` : Interface de publication de nouvelles annonces.
* `admin_dashboard.php` : Espace réservé à l'administration.
* `uploads/` : Stockage des photos de profil des utilisateurs.
* `assets/` : Fichiers de style (`style.css`), scripts et images.

## ⚙️ Installation

1.  **Prérequis :** Assurez-vous d'avoir un serveur local type **XAMPP** ou **WAMP** installé.
2.  **Clonage :** Clonez ce dépôt dans votre dossier `htdocs` (pour XAMPP).
3.  **Base de données :** Importez le fichier `covoiturage.sql` via **phpMyAdmin**.
4.  **Configuration :** Configurez vos accès SMTP dans le fichier de gestion des mails si vous souhaitez utiliser la fonctionnalité de récupération de mot de passe.
5.  **Lancement :** Accédez à `http://localhost/rydo/index.php` via votre navigateur.

## 🔒 Sécurité

* **Injection SQL :** Protection totale grâce à l'utilisation de requêtes préparées PDO.
* **Stockage :** Mots de passe cryptés avec `PASSWORD_DEFAULT`.
* **Uploads :** Filtrage strict des types MIME pour les images de profil.
* **Transactions :** Utilisation de `PDO::beginTransaction()` pour garantir l'intégrité des données lors des opérations sensibles.

## 👥 Auteurs

* **Mohamed ben Mohamed** - Étudiant ISET Sidi Bouzid
* **Khadraoui Wajih** - Étudiant ISET Sidi Bouzid

---
*Projet développé dans le cadre des travaux pratiques de développement web à l'ISET de Sidi Bouzid.*
