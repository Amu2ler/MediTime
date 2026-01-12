# MediTime - Plateforme de Prise de Rendez-vous Médicaux

MediTime est une application web complète permettant de simplifier la mise en relation entre patients et professionnels de santé. Ce projet est un clone avancé de Doctolib, réalisé avec **Laravel 12**.

## 🚀 Fonctionnalités Clés

### 👨‍⚕️ Pour les Médecins
*   **Gestion de profil** : Ajout d'informations professionnelles, adresse du cabinet, biographie.
*   **Attestation** : Téléchargement et validation de l'attestation d'exercice.
*   **Gestion des disponibilités** : Création de créneaux horaires (slots) flexibles.
*   **Tableau de bord** : Vue d'ensemble des rendez-vous, avec motif de consultation visible.
*   **Annulation** : Possibilité d'annuler un rendez-vous (notifie le patient et libère le créneau).

### 🏥 Pour les Patients
*   **Recherche avancée** : Trouver un médecin par nom, spécialité ou ville.
*   **Filtres de disponibilité** : Filtrer par "Aujourd'hui", "Cette semaine", etc.
*   **Prise de rendez-vous** : Réservation fluide avec choix du **motif de consultation**.
*   **Espace Patient** : Suivi des rendez-vous à venir et historique complet.

### 🛡️ Administration (Nouveau)
*   **Dashboard Moderne** : Statistiques globales (Médecins, Patients, RDV) avec graphiques visuels.
*   **Gestion des Utilisateurs** :
    *   Filtrage par rôle (Médecins / Patients).
    *   Tri dynamique par nom et date d'inscription.
    *   **Sécurité** : Impossible de supprimer un utilisateur ayant des rendez-vous futurs.
*   **Gestion des Spécialités** : Interface moderne en grille pour ajouter/modifier les spécialités.

## 🎨 Design & UX
Le projet respecte les codes visuels modernes (type Doctolib) :
*   Design épuré (Blanc / Bleu #0596de).
*   Feedback utilisateur clair (Messages de succès/erreur, Popups de confirmation).
*   Interface 100% responsive.

## 🛠️ Stack Technique

*   **Backend** : Laravel 12 (PHP 8.2+)
*   **Frontend** : Blade, Tailwind CSS, Alpine.js
*   **Base de données** : MySQL
*   **Authentification** : Laravel Breeze

## ⚙️ Installation

1.  **Cloner le projet**
    ```bash
    git clone https://github.com/votre-username/meditime.git
    cd meditime
    ```

2.  **Installer les dépendances**
    ```bash
    composer install
    npm install
    ```

3.  **Configuration de l'environnement**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configurez votre base de données dans le fichier `.env`.*

4.  **Base de données & Seeders**
    ```bash
    php artisan migrate --seed
    ```
    *Cela créera les tables et un jeu de données de test complet (admin, médecins, patients, créneaux).*

5.  **Lancer l'application**
    ```bash
    npm run build
    php artisan serve
    ```

## 🔐 Comptes de Test

Une fois les *seeders* lancés :

*   **Admin** : `admin@meditime.com` / `password`
*   **Patient** : `test@example.com` / `password`
*   **Médecins** : Générés aléatoirement (voir base de données).

## 📂 Structure du Projet

*   **Models** (`app/Models`) :
    *   `User` : Modèle unique (Rôles: admin, doctor, patient).
    *   `DoctorProfile` : Infos médecins & Attestation.
    *   `Specialty` : Spécialités médicales.
    *   `Slot` : Créneaux horaires.
    *   `Appointment` : Rendez-vous.
    *   `ConsultationReason` : Motifs de consultation.

*   **Contrôleurs** (`app/Http/Controllers`) :
    *   `Admin/` : Logique d'administration protégée.
    *   `DoctorSearchController` : Recherche et filtres.
    *   `BookingController` : Flux de réservation complet.
