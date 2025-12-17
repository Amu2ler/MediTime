<x-app-layout>
    <x-slot name="header">
        <h2>Modifier la spécialité</h2>
    </x-slot>

    <form method="POST" action="{{ route('specialties.update', $specialty) }}">
        @csrf
        @method('PUT')

        <label>Nom</label>
        <input type="text" name="name" value="{{ $specialty->name }}" required>

        <button type="submit">Mettre à jour</button>
    </form>
</x-app-layout>
