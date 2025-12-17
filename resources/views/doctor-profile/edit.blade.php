<x-app-layout>
    <x-slot name="header">
        <h2>Modifier mon profil médecin</h2>
    </x-slot>

    <form method="POST" action="{{ route('doctor.profile.update') }}">
        @csrf
        @method('PUT')

        <div>
            <label for="first_name">Prénom</label><br>
            <input
                type="text"
                id="first_name"
                name="first_name"
                value="{{ old('first_name', $profile->first_name) }}"
                required
            >
        </div>

        <div>
            <label for="last_name">Nom</label><br>
            <input
                type="text"
                id="last_name"
                name="last_name"
                value="{{ old('last_name', $profile->last_name) }}"
                required
            >
        </div>

        <div>
            <label for="phone">Téléphone</label><br>
            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone', $profile->phone) }}"
            >
        </div>

        <div>
            <label for="bio">Présentation</label><br>
            <textarea
                id="bio"
                name="bio"
                rows="4"
            >{{ old('bio', $profile->bio) }}</textarea>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit">
                Mettre à jour
            </button>
        </div>
    </form>
</x-app-layout>
