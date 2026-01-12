# 🎓 Guide Technique de Soutenance - Projet MediTime

Ce document est conçu pour t'aider à répondre aux questions techniques sur le projet **MediTime** (Clone Doctolib) développé sous **Laravel 12**.

---

## 1. Vue d'Ensemble
*   **Projet** : MediTime, plateforme de prise de rendez-vous médicaux.
*   **Technologies** : Laravel 12 (PHP Framework), Blade (Frontend Template), Tailwind CSS (Design), MySQL (Base de données).
*   **Pattern Architectural** : **MVC** (Modèle - Vue - Contrôleur).

---

## 2. Architecture MVC (Le cœur de la question)
Le prof va surement demander : *"Comment s'organise une page sur Laravel ?"*

Le pattern **MVC** sépare le code en 3 couches :

### **M**odèle (Données & Logique BDD)
Les modèles représentent tes tables dans la base de données. Ils sont dans `app/Models`.
*   **Exemples Concrets** :
    *   `User.php` : Gère les utilisateurs (médecins, patients, admins) et l'authentification.
    *   `Appointment.php` : Gère les rendez-vous. Il a des relations : `belongsTo(User::class, 'patient_id')`.
    *   `Slot.php` : Les créneaux horaires disponibles.

### **V**ue (Ce que l'utilisateur voit)
Les fichiers HTML/PHP qui affichent l'interface. Ils sont dans `resources/views`.
*   **Exemples Concrets** :
    *   `welcome.blade.php` : La page d'accueil.
    *   `admin/dashboard.blade.php` : Le tableau de bord administrateur qu'on vient de refaire.
    *   `doctor/search/index.blade.php` : La page de résultats de recherche.
*   **Blade** : C'est le moteur de template (`{{ $variable }}`, `@foreach`, `@if`). On utilise des **Components** (`<x-app-layout>`) pour ne pas répéter le header/footer.

### **C**ontrôleur (Le chef d'orchestre)
Il reçoit la requête de l'utilisateur, interroge le Modèle, et renvoie la Vue. Ils sont dans `app/Http/Controllers`.
*   **Scenario : Afficher la liste des médecins**
    1.  **Route** appelle `DoctorSearchController@index`.
    2.  **Controller** fait `User::where('role', 'doctor')->get()`.
    3.  **Controller** retourne `view('doctor.search.index', compact('doctors'))`.

---

## 3. Le Routing (`routes/web.php`)
C'est le fichier qui dit : *"Si je tape cette URL, execute ce code"*.

**Exemples du projet :**
```php
// Route simple (Page d'accueil)
Route::get('/', function () {
    return view('welcome');
});

// Route avec Contrôleur (Recherche)
Route::get('/search', [DoctorSearchController::class, 'index'])->name('doctor.search');

// Route protégée (Authentification requise)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', ...);
});
```
*   **Middleware** : C'est un gardien. `auth` vérifie si tu es connecté. Si non, il te redirige vers le login.

---

## 4. La Base de Données (MySQL)
Tu utilises **Eloquent ORM**, ce qui permet de manipuler la BDD comme des objets PHP sans écrire de SQL.

### Schéma Relationnel
*   **users** : Table centrale. Champ `role` ('patient', 'doctor', 'admin').
*   **doctor_profiles** : Extension pour les médecins (bio, adresse, infos). Relation `1-1` avec `users`.
*   **specialties** : Liste des spécialités (Cardiologue, etc.).
*   **slots** : Créneaux horaires créés par les médecins.
    *   *Champs* : `start_time`, `end_time`, `is_booked` (booléen).
*   **appointments** : Table de pivot/liaison.
    *   Lie un `patient_id` (User) à un `slot_id`.
    *   Contient le `reason` (motif).

### Relations (Eloquent)
*   Un **Médecin** a plusieurs **Slots** -> `$user->slots()`.
*   Un **Slot** a un **Rendez-vous** (Optionnel) -> `$slot->appointment`.
*   Un **Rendez-vous** appartient à un **Patient** -> `$appointment->patient()`.

---

## 5. Fonctionnalités Clés expliquées

### A. La Recherche (`DoctorSearchController`)
On utilise la méthode `when()` de Laravel pour filtrer conditionnellement.
*"Si l'utilisateur a tapé un nom, on ajoute un `where('name', 'like', ...)` au SQL."*

### B. Prise de Rendez-vous (`BookingController`)
C'est le processus critique :
1.  Le patient clique sur un créneau (Slot).
2.  On affiche le formulaire (`booking.create`).
3.  On valide le formulaire (`$request->validate()`).
4.  **Transaction BDD** :
    *   On crée l'Appointment.
    *   On marque le Slot comme `is_booked = true`.
    *   Si une étape échoue, tout est annulé (évite les doublons).

### C. L'Admin Panel
On a utilisé des fonctionnalités "CRUD" (Create, Read, Update, Delete) pour gérer les utilisateurs et les spécialités.
*   **Securité** : On empêche la suppression d'un médecin s'il a des RDV futurs (`hasMany` check).

---

## 6. Questions Pièges Fréquentes

**Q: C'est quoi Laravel Breeze ?**
R: C'est un kit de démarrage pour l'authentification. Il fournit tout prêt : Login, Register, Reset Password, et les Vues associées (avec Tailwind). Ça nous a fait gagner un temps fou.

**Q: C'est quoi une Migration ?**
R: C'est le versioning de la base de données. Au lieu de créer les tables à la main dans PHPMyAdmin, on écrit du code PHP (`Schema::create...`). Comme ça, si je te passe le projet, tu fais `php artisan migrate` et tu as la même structure que moi.

**Q: Comment tu gères les styles ?**
R: Avec **Tailwind CSS**. C'est du "Utility-first". Au lieu d'écrire une classe CSS `.btn-blue`, j'écris directement `<button class="bg-blue-500 text-white ...">`.

**Q: Et si je veux rajouter une colonne en base ?**
R: Je crée une nouvelle migration : `php artisan make:migration add_phone_to_users`.

---

## 💡 Astuce pour la démo
Montre le **Tableau de Bord Admin** ou le **Profil Médecin**. Ce sont les parties les plus visuelles et "finies" du projet. Insiste sur le fait que la plateforme est **Dynamique** (les créneaux disparaissent quand ils sont pris).
