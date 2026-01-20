# 📋 EXPLICATION DU PROJET MEDITIME - GUIDE POUR L'ORAL

## 🎯 1. VUE D'ENSEMBLE DU PROJET

**MediTime** est une application web de prise de rendez-vous médicaux inspirée de Doctolib, développée avec **Laravel 12**.

### Objectif
Permettre aux patients de rechercher des médecins, consulter leurs disponibilités et prendre rendez-vous en ligne.

### Technologies utilisées
- **Backend** : Laravel 12 (PHP 8.2)
- **Frontend** : Blade, TailwindCSS, Alpine.js
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze

---

## 🏗️ 2. ARCHITECTURE DU PROJET

### Structure MVC (Model-View-Controller)

```
app/
├── Models/              # Modèles Eloquent (tables BDD)
├── Http/
│   ├── Controllers/    # Logique métier
│   └── Middleware/     # Filtres de sécurité
resources/
└── views/              # Templates Blade
routes/
└── web.php             # Définition des routes
database/
├── migrations/         # Structure BDD
└── seeders/           # Données de test
```

---

## 📊 3. MODÈLES DE DONNÉES (RELATIONS)

### Modèles principaux

#### **User** (Utilisateurs)
- **Rôles** : `patient`, `doctor`, `admin`
- **Relations** :
  - `hasOne(DoctorProfile)` - Un médecin a un profil
  - `hasMany(Slot)` - Un médecin a plusieurs créneaux

#### **DoctorProfile** (Profil médecin)
- **Champs** : `specialty_id`, `phone`, `bio`, `address`, `city`, `zip_code`
- **Relations** :
  - `belongsTo(User)` - Appartient à un utilisateur
  - `belongsTo(Specialty)` - A une spécialité

#### **Specialty** (Spécialités médicales)
- **Champs** : `name`
- **Relations** :
  - `hasMany(DoctorProfile)` - Plusieurs médecins par spécialité
  - `hasMany(ConsultationReason)` - Motifs de consultation

#### **Slot** (Créneaux horaires)
- **Champs** : `user_id`, `start_time`, `end_time`, `is_booked`
- **Relations** :
  - `belongsTo(User)` - Appartient à un médecin
  - `hasOne(Appointment)` - Peut avoir un rendez-vous

#### **Appointment** (Rendez-vous)
- **Champs** : `patient_id`, `slot_id`, `status`, `reason`
- **Relations** :
  - `belongsTo(User, 'patient_id')` - Appartient à un patient
  - `belongsTo(Slot)` - Utilise un créneau

### Schéma des relations

```
User (1) ──< (1) DoctorProfile ──> (1) Specialty
User (1) ──< (*) Slot ──> (1) Appointment ──> (1) User (patient)
Specialty (1) ──< (*) ConsultationReason
```

---

## 🔐 4. SYSTÈME D'AUTHENTIFICATION ET SÉCURITÉ

### Middleware personnalisés

#### **RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`)
```php
public function handle(Request $request, Closure $next, string $role): Response
{
    $user = $request->user();
    
    if (!$user || $user->role !== $role) {
        abort(403); // Accès interdit
    }
    
    return $next($request);
}
```

**Fonction** : Vérifie que l'utilisateur a le bon rôle avant d'accéder à une route.

**Utilisation** :
```php
Route::middleware(['auth', 'role:doctor'])->group(function () {
    // Routes réservées aux médecins
});
```

### Protection des routes

1. **Routes publiques** : `/`, `/search`
2. **Routes authentifiées** : `middleware('auth')` - Tous les utilisateurs connectés
3. **Routes médecins** : `middleware(['auth', 'role:doctor'])` - Seulement médecins
4. **Routes admin** : `middleware(['auth', 'admin'])` - Seulement admins

---

## 🎯 5. FONCTIONNALITÉS PRINCIPALES

### A. Recherche de médecins (`DoctorSearchController`)

**Route** : `GET /search`

**Fonctionnalité** : Recherche avec filtres multiples

```php
$doctors = DoctorProfile::with(['user', 'specialty'])
    ->when($request->specialty_id, function ($query, $specialtyId) {
        $query->where('specialty_id', $specialtyId);
    })
    ->when($request->city, function ($query, $city) {
        $query->where('city', 'like', "%{$city}%");
    })
    ->when($request->availability, function ($query, $availability) {
        // Filtre par disponibilité (aujourd'hui, cette semaine)
    })
    ->get();
```

**Points techniques** :
- Utilisation de `when()` pour des filtres conditionnels
- Requêtes GET pour filtres partageables (URL avec paramètres)
- Eager loading (`with()`) pour optimiser les requêtes

### B. Prise de rendez-vous (`BookingController`)

#### Étape 1 : Affichage des créneaux (`create`)
```php
$slots = Slot::where('user_id', $doctor->user_id)
    ->where('is_booked', false)
    ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
    ->orderBy('start_time')
    ->get()
    ->groupBy(function ($slot) {
        return $slot->start_time->format('Y-m-d');
    });
```

**Fonctionnalités** :
- Affichage par semaine (navigation précédent/suivant)
- Groupement par date pour l'affichage calendrier
- Filtrage des créneaux déjà réservés

#### Étape 2 : Réservation (`store`)
```php
\DB::transaction(function () use ($request, $slot) {
    // Création du rendez-vous
    Appointment::create([
        'patient_id' => Auth::id(),
        'slot_id' => $slot->id,
        'reason' => $finalReason,
        'status' => 'confirmed',
    ]);
    
    // Marquage du créneau comme réservé
    $slot->update(['is_booked' => true]);
});
```

**Points techniques** :
- **Transaction** : Garantit que le rendez-vous ET le créneau sont mis à jour ensemble
- **Validation** : Vérification que le créneau existe et n'est pas déjà réservé
- **Gestion du réagencement** : Si `reschedule_id` existe, libère l'ancien créneau

### C. Dashboard personnalisé (`DashboardController`)

**Route** : `GET /dashboard`

**Fonctionnalité** : Affichage différent selon le rôle

```php
if ($user->role === 'doctor') {
    // Rendez-vous du médecin
    $appointments = Appointment::whereHas('slot', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->with(['patient', 'slot'])->get();
} else {
    // Rendez-vous du patient
    $appointments = Appointment::where('patient_id', $user->id)
        ->with(['slot.user.doctorProfile', 'slot.user'])
        ->get();
}
```

**Points techniques** :
- `whereHas()` : Requête sur relation
- Eager loading : Charge les relations nécessaires en une requête
- Tri par date : `sortBy('slot.start_time')`

### D. Annulation de rendez-vous (`AppointmentController`)

```php
public function destroy(Appointment $appointment)
{
    // Vérification d'autorisation
    if ($user->id !== $appointment->patient_id && 
        $user->id !== $appointment->slot->user_id) {
        abort(403);
    }
    
    // Libération du créneau
    $appointment->slot->update(['is_booked' => false]);
    
    // Suppression du rendez-vous
    $appointment->delete();
}
```

**Sécurité** : Vérifie que seul le patient ou le médecin peut annuler

---

## 🔄 6. FLUX DE DONNÉES - EXEMPLE COMPLET

### Scénario : Un patient prend rendez-vous

1. **Recherche** (`/search`)
   - Patient filtre par spécialité/ville
   - `DoctorSearchController::index()` retourne les médecins
   - Affichage avec créneaux disponibles

2. **Sélection d'un médecin**
   - Clic → `/booking/{doctor}`
   - `BookingController::create()` charge :
     - Les créneaux libres du médecin
     - Les motifs de consultation de sa spécialité

3. **Réservation**
   - Patient choisit un créneau et un motif
   - POST `/booking` → `BookingController::store()`
   - **Transaction** :
     - Création de `Appointment`
     - Mise à jour de `Slot` (`is_booked = true`)

4. **Confirmation**
   - Redirection vers `/dashboard`
   - `DashboardController::index()` affiche le nouveau rendez-vous

---

## 💡 7. POINTS TECHNIQUES IMPORTANTS À PRÉSENTER

### A. Eager Loading (Optimisation)
```php
->with(['user', 'specialty', 'user.slots'])
```
**Pourquoi** : Évite le problème N+1 (1 requête principale + N requêtes pour chaque relation)

### B. Transactions (Intégrité des données)
```php
\DB::transaction(function () {
    // Opérations atomiques
});
```
**Pourquoi** : Si une opération échoue, tout est annulé (pas de rendez-vous sans créneau réservé)

### C. Middleware (Sécurité)
```php
Route::middleware(['auth', 'role:doctor'])
```
**Pourquoi** : Protection des routes sensibles selon le rôle

### D. Validation (Sécurité)
```php
$request->validate([
    'slot_id' => ['required', 'exists:slots,id'],
    'reason_id' => ['required', 'exists:consultation_reasons,id'],
]);
```
**Pourquoi** : Vérifie que les données sont valides avant traitement

### E. Requêtes conditionnelles (Filtres)
```php
->when($request->city, function ($query, $city) {
    $query->where('city', 'like', "%{$city}%");
})
```
**Pourquoi** : Permet des filtres optionnels sans duplication de code

---

## 📝 8. STRUCTURE DES ROUTES

### Routes publiques
- `GET /` - Page d'accueil
- `GET /search` - Recherche de médecins

### Routes authentifiées
- `GET /dashboard` - Tableau de bord
- `GET /profile` - Profil utilisateur
- `GET /booking/{doctor}` - Prise de rendez-vous
- `POST /booking` - Confirmation rendez-vous
- `DELETE /appointments/{id}` - Annulation

### Routes médecins
- `GET /doctor/profile/create|edit` - Gestion profil
- `GET /doctor/slots` - Gestion créneaux
- `GET /doctor/patients` - Liste patients

### Routes admin
- `GET /admin` - Dashboard admin
- `GET /admin/users` - Gestion utilisateurs
- `GET /admin/specialties` - Gestion spécialités

---

## 🎨 9. INTERFACE UTILISATEUR

### Technologies frontend
- **Blade** : Templates PHP
- **TailwindCSS** : Styles utilitaires
- **Alpine.js** : Interactivité (dropdowns, modals)

### Composants réutilisables
- `<x-app-layout>` - Layout principal
- `<x-text-input>` - Champs de formulaire
- `<x-primary-button>` - Boutons

---

## 🗄️ 10. BASE DE DONNÉES

### Tables principales
1. `users` - Utilisateurs (patients, médecins, admins)
2. `doctor_profiles` - Profils médecins
3. `specialties` - Spécialités médicales
4. `slots` - Créneaux horaires
5. `appointments` - Rendez-vous
6. `consultation_reasons` - Motifs de consultation

### Migrations
- Structure créée via migrations Laravel
- Relations avec clés étrangères
- Contraintes d'intégrité (`cascadeOnDelete`)

---

## ✅ 11. POINTS FORTS À METTRE EN AVANT

1. **Architecture MVC propre** : Séparation logique/présentation
2. **Sécurité** : Middleware, validation, autorisations
3. **Optimisation** : Eager loading, requêtes efficaces
4. **Intégrité** : Transactions pour cohérence des données
5. **UX** : Interface intuitive, filtres avancés
6. **Code maintenable** : Relations Eloquent, composants réutilisables

---

## 🎤 12. DÉMONSTRATION RECOMMANDÉE

1. **Inscription** : Créer un compte patient
2. **Recherche** : Filtrer les médecins par spécialité
3. **Réservation** : Prendre un rendez-vous
4. **Dashboard** : Voir les rendez-vous
5. **Annulation** : Annuler un rendez-vous
6. **Admin** : Gérer les spécialités (si temps)

---

## 📚 13. VOCABULAIRE TECHNIQUE À UTILISER

- **MVC** : Modèle-Vue-Contrôleur
- **Eloquent ORM** : Mapping objet-relationnel
- **Middleware** : Filtre de requête
- **Eager Loading** : Chargement anticipé
- **Transaction** : Opération atomique
- **Route Model Binding** : Injection automatique de modèle
- **Validation** : Vérification des données
- **Authorization** : Vérification des permissions

---

## 🎯 CONCLUSION

Le projet démontre une bonne compréhension de :
- Laravel et son écosystème
- Architecture MVC
- Sécurité web
- Optimisation de requêtes
- Gestion de relations complexes
- Bonnes pratiques de développement
