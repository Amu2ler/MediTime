<x-app-layout>
    <x-slot name="header">
        <h2>Créer mon profil médecin</h2>
    </x-slot>

    <form method="POST" action="{{ route('doctor.profile.store') }}">
        @csrf

        <div>
            <label for="first_name">Prénom</label><br>
            <input type="text" name="first_name" id="first_name" required>
        </div>

        <div>
            <label for="last_name">Nom</label><br>
            <input type="text" name="last_name" id="last_name" required>
        </div>

        <div>
            <label for="phone">Téléphone</label><br>
            <input type="text" name="phone" id="phone">
        </div>

        <div>
            <label for="bio">Présentation</label><br>
            <textarea name="bio" id="bio" rows="4"></textarea>
        </div>

        <button type="submit">
            Enregistrer
        </button>
    </form>
</x-app-layout>
