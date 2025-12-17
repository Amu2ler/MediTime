<x-app-layout>
    <x-slot name="header">
        <h2>Ajouter une spécialité</h2>
    </x-slot>

    <form method="POST" action="{{ route('specialties.store') }}">
        @csrf

        <label>Nom de la spécialité</label>
        <input type="text" name="name" required>

        <button type="submit">Créer</button>
    </form>
</x-app-layout>
