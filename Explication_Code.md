# 💻 Explication par le Code - Projet MediTime

Ce document détaille les blocs de code critiques du projet. À utiliser pour montrer **CONCRÈTEMENT** comment ça marche lors de la soutenance.

---

## 1. MVC en Action : La Prise de Rendez-vous

C'est la fonctionnalité la plus complexe. Voici comment elle traverse les 3 couches MVC.

### A. La Route (`routes/web.php`)
```php
Route::middleware(['auth'])->group(function () {
    // Le {slot} est un paramètre dynamique (ID du créneau)
    Route::post('/booking/{slot}', [BookingController::class, 'store'])
        ->name('booking.store');
});
```
*   **Explication** : Quand on poste le formulaire, Laravel cherche le `BookingController` et la méthode `store`. Le middleware `auth` protège l'accès.

---

### B. Le Contrôleur (`app/Http/Controllers/BookingController.php`)
C'est le cerveau. Il valide et sauvegarde.

```php
public function store(Request $request, Slot $slot)
{
    // 1. Validation (Sécurité)
    $request->validate([
        'reason_id' => 'required|exists:consultation_reasons,id', // Vérifie que le motif existe
    ]);

    // 2. Transaction BDD (Intégrité)
    DB::transaction(function () use ($request, $slot) {
        
        // Empêcher le double booking (Race condition)
        if ($slot->is_booked) {
            throw new \Exception("Ce créneau est déjà pris.");
        }

        // Créer le Rendez-vous
        Appointment::create([
            'patient_id' => auth()->id(), // Utilisateur connecté
            'slot_id' => $slot->id,
            'reason_id' => $request->reason_id,
            'status' => 'confirmed',
        ]);

        // Verrouiller le créneau
        $slot->update(['is_booked' => true]);
    });

    return redirect()->route('dashboard')->with('success', 'Rendez-vous confirmé !');
}
```
*   **Points clés** :
    *   `DB::transaction` : Si le PC plante au milieu, rien n'est sauvegardé. C'est vital pour des données médicales.
    *   `Slot $slot` : Laravel fait du **Route Model Binding**. Il va chercher automatiquement le Slot en BDD grâce à l'ID dans l'URL.

---

### C. Le Modèle (`app/Models/User.php`)
Les relations permettent de naviguer entre les données.

```php
class User extends Authenticatable
{
    // Relation "Un Utilisateur (Médecin) a plusieurs Créneaux"
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    // Relation "Un Utilisateur a une Profil Docteur"
    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }
    
    // Accesseur pour vérifier le rôle facilement
    public function isDoctor()
    {
        return $this->role === 'doctor';
    }
}
```

---

## 2. Le Frontend : Blade & Tailwind

### Composant de Layout (`resources/views/layouts/app.blade.php`)
On utilise `{{ $slot }}` pour injecter le contenu. C'est comme le "body" d'une page HTML classique.

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation') <!-- Inclusion de la Navbar -->

            <!-- Header de Page -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Contenu Principal -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
```

### Boucle Blade (`resources/views/doctor/search/index.blade.php`)
Comment on affiche la liste des médecins ?

```html
<!-- On boucle sur la variable $doctors passée par le Controller -->
@forelse ($doctors as $doctor)
    
    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
        <!-- Nom du Médecin -->
        <h3 class="text-xl font-bold text-gray-900">
            Dr. {{ $doctor->name }}
        </h3>
        
        <!-- Spécialité (Via relation doctorProfile) -->
        <p class="text-blue-600 font-medium">
            {{ $doctor->doctorProfile->specialty->name ?? 'Généraliste' }}
        </p>

        <!-- Bouton de RDV -->
        <a href="{{ route('doctor.show', $doctor) }}" 
           class="mt-4 block w-full text-center bg-cyan-600 text-white py-2 rounded-lg hover:bg-cyan-700">
            Prendre Rendez-vous
        </a>
    </div>

@empty
    <!-- Si la liste est vide -->
    <p class="text-gray-500 text-center col-span-3">Aucun médecin trouvé.</p>
@endforelse
```

---

## 3. Base de Données : Migration

Exemple de la table `appointments` (`database/migrations/...create_appointments_table.php`).

```php
public function up(): void
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id(); // Clé primaire (Auto-increment)
        
        // Clés étrangères (Relations)
        $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('slot_id')->constrained()->onDelete('cascade');
        
        // Champs simples
        $table->string('status')->default('confirmed');
        $table->timestamps(); // created_at, updated_at
    });
}
```
*   `constrained()` : Laravel comprend tout seul qu'il faut lier à la table `users`.
*   `onDelete('cascade')` : Si on supprime le patient, ses RDV disparaissent aussi (Nettoyage automatique).
