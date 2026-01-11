# MediTime - Plateforme de Prise de Rendez-vous Médicaux

MediTime est une application web complète permettant de simplifier la mise en relation entre patients et professionnels de santé. Ce projet est un clone simplifié de Doctolib, réalisé avec **Laravel 12**.

## 🚀 Fonctionnalités

### 👨‍⚕️ Pour les Médecins
*   **Gestion de profil** : Ajout d'informations professionnelles, adresse du cabinet, biographie.
*   **Gestion des disponibilités** : Création de créneaux horaires (slots) pour les rendez-vous.
*   **Tableau de bord** : Vue d'ensemble des prochains rendez-vous confirmés.
*   **Annulation** : Possibilité d'annuler un rendez-vous (libère le créneau).

### 🏥 Pour les Patients
*   **Recherche avancée** : Trouver un médecin par nom, spécialité ou ville.
*   **Prise de rendez-vous** : Réservation simple et rapide sur les créneaux disponibles.
*   **Tableau de bord** : Suivi des rendez-vous à venir et historique.
*   **Annulation** : Gestion autonome des annulations.

### 🛡️ Administration
*   **Dashboard Admin** : Statistiques globales de la plateforme.
*   **Gestion des utilisateurs** : Vue d'ensemble et suppression de comptes.
*   **Gestion des spécialités** : Ajout, modification et suppression des spécialités médicales disponibles.

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
    *Cela créera les tables et un jeu de données de test (admin, patients, médecins, spécialités).*

5.  **Lancer l'application**
    ```bash
    npm run build
    php artisan serve
    ```

## 🔐 Comptes de Test

Une fois les *seeders* lancés, vous pouvez utiliser ces comptes :

*   **Admin** : `admin@meditime.com` / `password`
*   **Patient** : `test@example.com` / `password`

## 📂 Structure du Projet

Les fichiers clés du projet sont organisés comme suit :

*   **Models** (`app/Models`) :
    *   `User` : Modèle unique pour tous les rôles (Patient, Doctor, Admin).
    *   `DoctorProfile` : Informations spécifiques aux médecins (liée à User).
    *   `Specialty` : Spécialités médicales.
    *   `Slot` : Créneaux horaires de disponibilité.
    *   `Appointment` : Rendez-vous liant un Patient et un Slot.

*   **Contrôleurs** (`app/Http/Controllers`) :
    *   `Admin/` : Logique réservée aux administrateurs.
    *   `DoctorSearchController` : Moteur de recherche.
    *   `BookingController` : Gestion de la prise de rendez-vous.
    *   `SlotController` : Gestion des créneaux médecins.

*   **Vues** (`resources/views`) :
    *   `admin/` : Interfaces d'administration.
    *   `doctor/` : Interfaces spécifiques aux médecins.
    *   `doctor-search/` : Page de recherche publique.
